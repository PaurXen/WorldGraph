<?php

declare(strict_types=1);

use WorldGraph\Core\Http\Router;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$expectSame = static function (mixed $expected, mixed $actual): void {
    if ($expected !== $actual) {
        throw new RuntimeException(
            'Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true),
        );
    }
};

$expectInvalidArgument = static function (callable $operation): void {
    try {
        $operation();
    } catch (InvalidArgumentException) {
        return;
    }

    throw new RuntimeException('Expected InvalidArgumentException.');
};

$echoParameters = static fn (array $parameters, array $query): array => [$parameters, $query];

$tests = [
    'static routes resolve without executing handlers' => static function () use ($expectSame): void {
        $router = new Router();
        $calls = 0;
        $router->add('GET', '/', static function (array $parameters, array $query) use (&$calls): string {
            ++$calls;

            return 'home';
        });

        $route = $router->resolve('GET', '/');
        $expectSame(200, $route['status']);
        $expectSame(0, $calls);
        $expectSame('home', ($route['handler'])($route['parameters'], $route['query']));
        $expectSame(1, $calls);
    },
    'methods select separate operations and remain case-sensitive' => static function () use ($expectSame): void {
        $router = new Router();
        $router->add('GET', '/items', static fn (): string => 'read');
        $router->add('POST', '/items', static fn (): string => 'create');

        $get = $router->resolve('GET', '/items');
        $post = $router->resolve('POST', '/items');
        $expectSame('read', ($get['handler'])($get['parameters'], $get['query']));
        $expectSame('create', ($post['handler'])($post['parameters'], $post['query']));
        $expectSame(405, $router->resolve('get', '/items')['status']);
    },
    'numeric method tokens remain strings in the allowed methods list' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->add('123', '/tokens', $echoParameters);

        $expectSame(200, $router->resolve('123', '/tokens')['status']);
        $route = $router->resolve('GET', '/tokens');
        $expectSame(405, $route['status']);
        $expectSame(['123'], $route['allowedMethods']);
    },
    'static paths treat regex metacharacters literally' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->add('GET', '/notes/a.b+', $echoParameters);

        $expectSame(200, $router->resolve('GET', '/notes/a.b+')['status']);
        $expectSame(404, $router->resolve('GET', '/notes/axb')['status']);
    },
    'named path parameters and query remain separate' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('GET', '~^/worlds/(?P<id>[1-9][0-9]*)$~', $echoParameters);

        $route = $router->resolve('GET', '/worlds/42?id=99&page=2');
        $expectSame(200, $route['status']);
        $expectSame(
            [['id' => '42'], ['id' => '99', 'page' => '2']],
            ($route['handler'])($route['parameters'], $route['query']),
        );
    },
    'regex constraints reject identifiers outside the registered format' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('GET', '~^/worlds/(?P<id>[1-9][0-9]*)$~', $echoParameters);

        foreach (['/worlds/abc', '/worlds/0', '/worlds/-1', '/worlds/01'] as $target) {
            $expectSame(404, $router->resolve('GET', $target)['status']);
        }
        $expectSame(200, $router->resolve('GET', '/worlds/42')['status']);
    },
    'regex must match the entire path' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('GET', '~/worlds/(?P<id>[0-9]+)~', $echoParameters);

        $expectSame(200, $router->resolve('GET', '/worlds/42')['status']);
        $expectSame(404, $router->resolve('GET', '/prefix/worlds/42')['status']);
        $expectSame(404, $router->resolve('GET', '/worlds/42/extra')['status']);
    },
    'query is decoded independently of matching the path' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->add('GET', '/', $echoParameters);

        $route = $router->resolve('GET', '/?q=two+words&tags[]=race&tags[]=location&literal=%2B');
        $expectSame(200, $route['status']);
        $expectSame([], $route['parameters']);
        $expectSame(
            ['q' => 'two words', 'tags' => ['race', 'location'], 'literal' => '+'],
            $route['query'],
        );
    },
    'captured values decode Unicode and preserve path plus signs' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('GET', '~^/cards/(?P<name>[^/]+)$~', $echoParameters);

        $expectSame(
            ['name' => 'Żmija A+B'],
            $router->resolve('GET', '/cards/%C5%BBmija%20A%2BB')['parameters'],
        );
        $expectSame(['name' => 'a+b'], $router->resolve('GET', '/cards/a+b')['parameters']);
    },
    'encoded slash remains parameter data and decoding happens once' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('GET', '~^/cards/(?P<name>[^/]+)$~', $echoParameters);

        $expectSame(['name' => 'a/b'], $router->resolve('GET', '/cards/a%2Fb')['parameters']);
        $expectSame(['name' => 'a%2Fb'], $router->resolve('GET', '/cards/a%252Fb')['parameters']);
        $expectSame(404, $router->resolve('GET', '/cards/a/b')['status']);
    },
    'unmatched optional named parameters are null' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('GET', '~^/items(?:/(?P<id>[0-9]+))?$~', $echoParameters);

        $expectSame(['id' => null], $router->resolve('GET', '/items')['parameters']);
        $expectSame(['id' => '7'], $router->resolve('GET', '/items/7')['parameters']);
    },
    'static routes take priority over earlier regex routes' => static function () use ($expectSame): void {
        $router = new Router();
        $router->addRegex('GET', '~^/worlds/(?P<id>[^/]+)$~', static fn (): string => 'dynamic');
        $router->add('GET', '/worlds/new', static fn (): string => 'static');

        $route = $router->resolve('GET', '/worlds/new');
        $expectSame('static', ($route['handler'])($route['parameters'], $route['query']));
    },
    'static path does not fall through to regex on a wrong method' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('POST', '~^/worlds/(?P<id>[^/]+)$~', $echoParameters);
        $router->add('GET', '/worlds/new', $echoParameters);

        $route = $router->resolve('POST', '/worlds/new');
        $expectSame(405, $route['status']);
        $expectSame(null, $route['handler']);
        $expectSame(['GET', 'HEAD'], $route['allowedMethods']);
    },
    'overlapping regex routes use the first one supporting the method' => static function () use ($expectSame): void {
        $router = new Router();
        $router->addRegex('GET', '~^/items/(?P<id>[^/]+)$~', static fn (): string => 'first');
        $router->addRegex('GET', '~^/items/(?P<id>[0-9]+)$~', static fn (): string => 'second');
        $router->addRegex('POST', '~^/items/(?P<id>[0-9]+)$~', static fn (): string => 'create');

        $get = $router->resolve('GET', '/items/7');
        $post = $router->resolve('POST', '/items/7');
        $expectSame('first', ($get['handler'])($get['parameters'], $get['query']));
        $expectSame('create', ($post['handler'])($post['parameters'], $post['query']));
    },
    'wrong method reports all unique matching regex methods' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->addRegex('GET', '~^/items/(?P<id>[^/]+)$~', $echoParameters);
        $router->addRegex('GET', '~^/items/(?P<id>[0-9]+)$~', $echoParameters);
        $router->addRegex('POST', '~^/items/(?P<id>[0-9]+)$~', $echoParameters);
        $router->addRegex('DELETE', '~^/items/(?P<number>\d+)$~', $echoParameters);

        $route = $router->resolve('PATCH', '/items/7');
        $expectSame(405, $route['status']);
        $expectSame(null, $route['handler']);
        $expectSame(['DELETE', 'GET', 'HEAD', 'POST'], $route['allowedMethods']);
    },
    'HEAD falls back to GET and an explicit HEAD route wins' => static function () use ($expectSame): void {
        $router = new Router();
        $router->add('GET', '/', static fn (): string => 'get');
        $fallback = $router->resolve('HEAD', '/');
        $expectSame(200, $fallback['status']);
        $expectSame('get', ($fallback['handler'])($fallback['parameters'], $fallback['query']));

        $router->add('HEAD', '/', static fn (): string => 'head');
        $explicit = $router->resolve('HEAD', '/');
        $expectSame('head', ($explicit['handler'])($explicit['parameters'], $explicit['query']));
    },
    'explicit regex HEAD wins over an earlier GET fallback' => static function () use ($expectSame): void {
        $router = new Router();
        $router->addRegex('GET', '~^/items/(?P<id>[^/]+)$~', static fn (): string => 'get');
        $router->addRegex('HEAD', '~^/items/(?P<id>[0-9]+)$~', static fn (): string => 'head');

        $route = $router->resolve('HEAD', '/items/7');
        $expectSame('head', ($route['handler'])($route['parameters'], $route['query']));
    },
    'unknown paths report 404 without a handler' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->add('GET', '/', $echoParameters);

        $route = $router->resolve('GET', '/missing');
        $expectSame(404, $route['status']);
        $expectSame(null, $route['handler']);
        $expectSame([], $route['allowedMethods']);
    },
    'trailing slash is significant' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->add('GET', '/items', $echoParameters);

        $expectSame(200, $router->resolve('GET', '/items')['status']);
        $expectSame(404, $router->resolve('GET', '/items/')['status']);
    },
    'malformed request targets report 400' => static function () use ($expectSame, $echoParameters): void {
        $router = new Router();
        $router->add('GET', '/', $echoParameters);

        foreach (['', 'relative', 'https://example.test/', '/bad path', "/bad\t", "/bad\n", '/bad\\path', '/bad#fragment', '/bad%', '/bad%2', '/bad%GG', '/?q=%XX'] as $target) {
            $route = $router->resolve('GET', $target);
            $expectSame(400, $route['status']);
            $expectSame(null, $route['handler']);
        }
    },
    'invalid configuration is rejected' => static function () use ($expectInvalidArgument, $echoParameters): void {
        $router = new Router();
        $expectInvalidArgument(static fn () => $router->add('GET', '/items?query=1', $echoParameters));
        $expectInvalidArgument(static fn () => $router->add('GET', 'relative', $echoParameters));
        $expectInvalidArgument(static fn () => $router->addRegex('GET', '~[~', $echoParameters));
        $expectInvalidArgument(static fn () => $router->add('GET POST', '/', $echoParameters));

        $router->add('GET', '/items', $echoParameters);
        $expectInvalidArgument(static fn () => $router->add('GET', '/items', $echoParameters));
        $router->addRegex('GET', '~^/items/(?P<id>[0-9]+)$~', $echoParameters);
        $expectInvalidArgument(static fn () => $router->addRegex('GET', '~^/items/(?P<id>[0-9]+)$~', $echoParameters));
    },
    'query cannot choose another route or file' => static function () use ($expectSame): void {
        $router = new Router();
        $router->add('GET', '/', static fn (): string => 'home');
        $router->add('GET', '/other', static fn (): string => 'other');

        $route = $router->resolve('GET', '/?route=/other&file=../composer.json');
        $expectSame('home', ($route['handler'])($route['parameters'], $route['query']));
        $expectSame(['route' => '/other', 'file' => '../composer.json'], $route['query']);
    },
];

$failures = [];
foreach ($tests as $name => $test) {
    try {
        $test();
    } catch (Throwable $error) {
        $failures[] = $name . ': ' . $error->getMessage();
    }
}

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    exit(1);
}

printf("Router tests: %d scenarios passed.\n", count($tests));
