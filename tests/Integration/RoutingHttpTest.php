<?php

declare(strict_types=1);

$expect = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$readFixture = static function (string $path): string {
    $contents = @file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Could not read local fixture: ' . basename($path));
    }

    return $contents;
};

/** @return array{status: int, headers: array<string, list<string>>, body: string} */
$request = static function (string $baseUrl, string $method, string $target, ?string $body): array {
    $headers = [
        'Connection: close',
        'Accept-Encoding: identity',
    ];
    $options = [
        'method' => $method,
        'protocol_version' => 1.1,
        'ignore_errors' => true,
        'follow_location' => 0,
        'max_redirects' => 0,
        'timeout' => 5.0,
    ];
    if ($body !== null) {
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Content-Length: ' . strlen($body);
        $options['content'] = $body;
    }
    $options['header'] = implode("\r\n", $headers);

    $stream = @fopen($baseUrl . $target, 'rb', false, stream_context_create(['http' => $options]));
    if ($stream === false) {
        throw new RuntimeException('Could not open HTTP response; check the server and allow_url_fopen.');
    }

    try {
        $responseBody = @stream_get_contents($stream);
        $metadata = stream_get_meta_data($stream);
    } finally {
        fclose($stream);
    }
    if ($responseBody === false || ($metadata['timed_out'] ?? false)) {
        throw new RuntimeException('Could not read the complete HTTP response within the timeout.');
    }

    $status = null;
    $responseHeaders = [];
    foreach ($metadata['wrapper_data'] ?? [] as $line) {
        if (preg_match('~^HTTP/\d+(?:\.\d+)? ([0-9]{3})(?:\s|$)~', $line, $matches) === 1) {
            $status = (int) $matches[1];
            $responseHeaders = [];
            continue;
        }

        $parts = explode(':', $line, 2);
        if (count($parts) === 2) {
            $responseHeaders[strtolower(trim($parts[0]))][] = trim($parts[1]);
        }
    }
    if ($status === null) {
        throw new RuntimeException('HTTP response has no valid status line.');
    }

    return ['status' => $status, 'headers' => $responseHeaders, 'body' => $responseBody];
};

try {
    if (count($argv) > 2) {
        throw new RuntimeException('Usage: php tests/Integration/RoutingHttpTest.php [base-url]');
    }
    $baseUrl = rtrim($argv[1] ?? 'http://nginx', '/');
    $url = parse_url($baseUrl);
    if ($url === false
        || !in_array($url['scheme'] ?? '', ['http', 'https'], true)
        || ($url['host'] ?? '') === ''
        || isset($url['user'])
        || isset($url['pass'])
        || isset($url['path'])
        || isset($url['query'])
        || isset($url['fragment'])
    ) {
        throw new RuntimeException('Base URL must be an HTTP(S) origin without credentials, path, query or fragment.');
    }

    $projectRoot = dirname(__DIR__, 2);
    $css = $readFixture($projectRoot . '/public/assets/css/style.css');
} catch (Throwable $error) {
    fwrite(STDERR, 'Routing HTTP tests: ' . $error->getMessage() . PHP_EOL);
    exit(1);
}

$home = ['status' => 200, 'home' => true, 'charset' => 'utf-8'];
$emptyPhp = ['body' => '', 'charset' => 'utf-8'];
$methodNotAllowed = ['status' => 405, 'allow' => ['GET', 'HEAD'], ...$emptyPhp];
$notFound = ['status' => 404, ...$emptyPhp];
$tests = [
    'GET /' => $home,
    'GET /index.php' => $home,
    'GET /index.html' => $home,
    'GET /assets/css/style.css' => ['status' => 200, 'type' => 'text/css', 'body' => $css],
    'GET /?q=myra&id=99' => $home,
    'GET /index.html?q=myra&id=99' => $home,
    'GET /?view=../composer.json&layout=../composer.json&title=%3Cscript%3Eunexpected%3C/script%3E' => $home,
    'HEAD /' => ['status' => 200, ...$emptyPhp],
    'HEAD /index.php' => ['status' => 200, ...$emptyPhp],
    'HEAD /index.html' => ['status' => 200, ...$emptyPhp],
    'POST /' => [...$methodNotAllowed, 'requestBody' => 'routing-http-test=1'],
    'POST /index.html' => [...$methodNotAllowed, 'requestBody' => 'routing-http-test=1'],
    'PUT /' => $methodNotAllowed,
    'OPTIONS /' => $methodNotAllowed,
    'OPTIONS /index.html' => $methodNotAllowed,
    'DELETE /' => $methodNotAllowed,
    'PATCH /' => $methodNotAllowed,
    'GET /worlds/42?tab=graph' => $notFound,
    'POST /worlds/42?tab=graph' => [...$notFound, 'requestBody' => 'routing-http-test=1'],
    'GET /?q=%GG' => ['status' => 400, ...$emptyPhp],
    'GET /.env' => ['status' => 403],
    'GET /assets/css/.gitkeep' => ['status' => 403],
    'GET /composer.json' => ['status' => 404],
    'GET /config/routes.php' => ['status' => 404],
    'GET /src/Core/Http/Router.php' => ['status' => 404],
    'GET /templates/home.php' => ['status' => 404],
    'GET /templates/layouts/main.php' => ['status' => 404],
    'GET /templates/partials/navigation.php' => ['status' => 404],
    'GET /vendor/autoload.php' => ['status' => 404],
    'GET /missing.php' => ['status' => 404],
];

$failures = [];
$canonicalHome = null;
foreach ($tests as $name => $expected) {
    try {
        [$method, $target] = explode(' ', $name, 2);
        $response = $request($baseUrl, $method, $target, $expected['requestBody'] ?? null);
        $contentTypes = $response['headers']['content-type'] ?? [];
        $contentType = implode(', ', $contentTypes);
        $details = sprintf(
            'HTTP %d; Content-Type %s; %d bytes; SHA-256 %s',
            $response['status'],
            json_encode($contentType, JSON_INVALID_UTF8_SUBSTITUTE),
            strlen($response['body']),
            hash('sha256', $response['body']),
        );

        $expect($response['status'] === $expected['status'], 'Expected HTTP ' . $expected['status'] . '; ' . $details);
        $expect(count($contentTypes) === 1, 'Expected one Content-Type header; ' . $details);
        $type = strtolower(trim(explode(';', $contentType, 2)[0]));
        $expectedType = $expected['type'] ?? 'text/html';
        $expect($type === $expectedType, 'Expected MIME type ' . $expectedType . '; ' . $details);

        if (isset($expected['charset'])) {
            $expect(
                preg_match('/(?:^|;)\s*charset\s*=\s*"?utf-8"?(?:\s*;|\s*$)/i', $contentType) === 1,
                'Expected UTF-8 charset; ' . $details,
            );
        }
        if (array_key_exists('body', $expected)) {
            $expect(
                $response['body'] === $expected['body'],
                sprintf(
                    'Expected %d bytes with SHA-256 %s; %s',
                    strlen($expected['body']),
                    hash('sha256', $expected['body']),
                    $details,
                ),
            );
        }
        if ($expected['home'] ?? false) {
            foreach (['html', 'head', 'body', 'header', 'nav', 'main', 'footer'] as $element) {
                $expect(preg_match_all('~<' . $element . '\\b~i', $response['body']) === 1, 'Expected a single ' . $element . ' element; ' . $details);
                $expect(preg_match_all('~</' . $element . '\\s*>~i', $response['body']) === 1, 'Expected a single closing ' . $element . ' element; ' . $details);
            }
            foreach ([
                '<!DOCTYPE html>',
                '<html lang="pl">',
                '<title>WorldGraph — wszystko jest połączone</title>',
                '<link rel="stylesheet" href="/assets/css/style.css">',
                '<a class="skip-link" href="#main">',
                '<main id="main">',
                'Twój świat.<br>Wszystko jest<br><em>połączone.</em>',
                'id="graph-title">Powiązania Elary w świecie Asteria</title>',
                'id="idea"',
                'href="/#idea"',
                'href="/#world-preview"',
            ] as $fragment) {
                $expect(str_contains($response['body'], $fragment), 'Missing expected home page markup ' . json_encode($fragment, JSON_UNESCAPED_UNICODE) . '; ' . $details);
            }
            if ($canonicalHome === null) {
                $canonicalHome = $response['body'];
            } else {
                $expect($response['body'] === $canonicalHome, 'Home aliases and query variants must return identical HTML; ' . $details);
            }
        }
        if (isset($expected['allow'])) {
            $allowed = array_map('trim', explode(',', implode(',', $response['headers']['allow'] ?? [])));
            sort($allowed, SORT_STRING);
            $expect($allowed === $expected['allow'], 'Expected Allow: GET, HEAD; ' . $details);
        }
    } catch (Throwable $error) {
        $failures[] = $name . ': ' . $error->getMessage();
    }
}

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    fprintf(STDERR, "Routing HTTP tests: %d of %d scenarios failed.\n", count($failures), count($tests));
    exit(1);
}

printf("Routing HTTP tests: %d scenarios passed.\n", count($tests));
