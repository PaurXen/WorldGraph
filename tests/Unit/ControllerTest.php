<?php

declare(strict_types=1);

use WorldGraph\Controller\BaseController;
use WorldGraph\Controller\HomeController;
use WorldGraph\Core\Http\Router;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$expectSame = static function (mixed $expected, mixed $actual): void {
    if ($expected !== $actual) {
        throw new RuntimeException(
            'Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true),
        );
    }
};

$expectException = static function (string $expectedClass, callable $operation): Throwable {
    try {
        $operation();
    } catch (Throwable $error) {
        if (!$error instanceof $expectedClass) {
            throw new RuntimeException('Expected ' . $expectedClass . ', got ' . $error::class . '.', 0, $error);
        }

        return $error;
    }

    throw new RuntimeException('Expected ' . $expectedClass . '.');
};

$capture = static function (callable $operation): string {
    $initialLevel = ob_get_level();
    ob_start();

    try {
        $operation();
        if (ob_get_level() !== $initialLevel + 1) {
            throw new RuntimeException('Rendering changed the surrounding output buffer level.');
        }
        $output = ob_get_contents();
        if ($output === false) {
            throw new RuntimeException('Could not read the captured output.');
        }

        return $output;
    } finally {
        while (ob_get_level() > $initialLevel) {
            ob_end_clean();
        }
    }
};

$createdFiles = [];
$createdDirectories = [];
$failures = [];
$skipped = [];
$tests = [];

try {
    $projectRoot = dirname(__DIR__, 2);
    $fixtureName = '_controller_test_' . bin2hex(random_bytes(12));
    $fixtureDirectory = $projectRoot . '/templates/' . $fixtureName;
    if (!mkdir($fixtureDirectory, 0700)) {
        throw new RuntimeException('Could not create a unique template fixture directory.');
    }
    $createdDirectories[] = $fixtureDirectory;

    foreach (['nested', 'directory.php'] as $directory) {
        $path = $fixtureDirectory . '/' . $directory;
        if (!mkdir($path, 0700)) {
            throw new RuntimeException('Could not create a template fixture subdirectory.');
        }
        $createdDirectories[] = $path;
    }

    $writeFixture = static function (string $name, string $contents) use ($fixtureDirectory, &$createdFiles): void {
        $path = $fixtureDirectory . '/' . $name . '.php';
        $file = fopen($path, 'xb');
        if ($file === false) {
            throw new RuntimeException('Could not exclusively create template fixture: ' . $name);
        }
        $createdFiles[] = $path;

        try {
            if (fwrite($file, $contents) !== strlen($contents)) {
                throw new RuntimeException('Could not write the complete template fixture: ' . $name);
            }
        } finally {
            fclose($file);
        }
    };

    $writeFixture('defaults', '<?php echo json_encode($data, JSON_THROW_ON_ERROR);');
    $writeFixture('raw', '<?php echo $data["message"];');
    $writeFixture('escaped', '<?php echo htmlspecialchars($data["message"], ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");');
    $writeFixture('nested/view_2-test', '<?php echo $data["nested"]["message"];');
    $writeFixture('scope', <<<'PHP'
<?php
echo json_encode([
    'controller' => isset($this),
    'extracted' => isset($message),
    'caller' => isset($callerValue),
    'view' => isset($view),
    'message' => $data['message'],
], JSON_THROW_ON_ERROR);
PHP);
    $writeFixture('throws', '<?php echo "partial"; throw new LogicException("Template failure.");');
    $writeFixture('nested-throws', <<<'PHP'
<?php
echo 'partial';
ob_start();
echo 'nested';
ob_start();
echo 'deep';
throw new LogicException('Nested template failure.');
PHP);
    $writeFixture('unbalanced', <<<'PHP'
<?php
echo 'partial';
ob_start();
echo 'nested';
ob_start();
echo 'deep';
PHP);
    $writeFixture('balanced', <<<'PHP'
<?php
echo 'before|';
ob_start();
echo 'nested';
$nested = ob_get_clean();
echo $nested, '|after';
PHP);

    $controller = new class extends BaseController {
        public function show(string $view, array $data = []): void
        {
            $this->render($view, $data);
        }
    };

    $tests = [
        'views receive an empty data array by default' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $expectSame('[]', $capture(static fn () => $controller->show($fixtureName . '/defaults')));
        },
        'render preserves data values for the view' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $value = '<strong title="test">Żmija & Asteria</strong>';
            $expectSame($value, $capture(static fn () => $controller->show($fixtureName . '/raw', ['message' => $value])));
        },
        'views can escape text for their HTML context' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $expectSame(
                '&lt;script&gt;&quot;Żmija&quot; &amp; &#039;Asteria&#039;&lt;/script&gt;',
                $capture(static fn () => $controller->show($fixtureName . '/escaped', ['message' => '<script>"Żmija" & \'Asteria\'</script>'])),
            );
        },
        'logical names allow nested views and safe segment characters' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $expectSame(
                'nested value',
                $capture(static fn () => $controller->show($fixtureName . '/nested/view_2-test', ['nested' => ['message' => 'nested value']])),
            );
        },
        'templates cannot access the controller or extracted data variables' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $callerValue = 'caller value';
            $output = $capture(static fn () => $controller->show($fixtureName . '/scope', ['message' => $callerValue]));
            $expectSame(
                ['controller' => false, 'extracted' => false, 'caller' => false, 'view' => false, 'message' => 'caller value'],
                json_decode($output, true, 512, JSON_THROW_ON_ERROR),
            );
        },
        'data keys cannot replace the selected template file' => static function () use ($controller, $fixtureName, $fixtureDirectory, $capture, $expectSame): void {
            $expectSame('selected view', $capture(static fn () => $controller->show($fixtureName . '/raw', [
                'message' => 'selected view',
                'templateFile' => $fixtureDirectory . '/throws.php',
                'view' => '../public/index',
            ])));
        },
        'the same view executes again with fresh data' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $output = $capture(static function () use ($controller, $fixtureName): void {
                $controller->show($fixtureName . '/raw', ['message' => 'first|']);
                $controller->show($fixtureName . '/raw', ['message' => 'second']);
            });
            $expectSame('first|second', $output);
        },
        'invalid names cannot select arbitrary files or stream wrappers' => static function () use ($controller, $fixtureName, $capture, $expectSame, $expectException): void {
            $names = [
                '', '.', '..', '../public/index', $fixtureName . '/../home',
                '/home', '//home', 'C:/templates/home', 'C:\\templates\\home',
                'php://filter/resource=home', 'file:///etc/passwd', 'https://example.test/view',
                'home.php', './home', 'nested//home', 'nested/./home', 'nested/../home',
                'nested\\home', 'home/', "home\0", "home\n", 'home?view=other',
                '%2e%2e/home', 'home name',
            ];
            $expectSame('', $capture(static function () use ($controller, $names, $expectException): void {
                foreach ($names as $name) {
                    $expectException(InvalidArgumentException::class, static fn () => $controller->show($name));
                }
            }));
        },
        'missing views fail without output' => static function () use ($controller, $fixtureName, $capture, $expectSame, $expectException): void {
            $expectSame('', $capture(static fn () => $expectException(
                RuntimeException::class,
                static fn () => $controller->show($fixtureName . '/missing'),
            )));
        },
        'a directory cannot be rendered as a template' => static function () use ($controller, $fixtureName, $capture, $expectSame, $expectException): void {
            $expectSame('', $capture(static fn () => $expectException(
                RuntimeException::class,
                static fn () => $controller->show($fixtureName . '/directory'),
            )));
        },
        'template exceptions discard partial output and preserve the caller buffer' => static function () use ($controller, $fixtureName, $capture, $expectSame, $expectException): void {
            $output = $capture(static function () use ($controller, $fixtureName, $expectSame, $expectException): void {
                echo 'caller before|';
                $initialLevel = ob_get_level();
                $error = $expectException(LogicException::class, static fn () => $controller->show($fixtureName . '/throws'));
                $expectSame('Template failure.', $error->getMessage());
                $expectSame($initialLevel, ob_get_level());
                echo '|caller after';
            });
            $expectSame('caller before||caller after', $output);
        },
        'successful rendering appends to the caller buffer without closing it' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $output = $capture(static function () use ($controller, $fixtureName, $expectSame): void {
                echo 'caller before|';
                $initialLevel = ob_get_level();
                $controller->show($fixtureName . '/raw', ['message' => 'rendered']);
                $expectSame($initialLevel, ob_get_level());
                echo '|caller after';
            });
            $expectSame('caller before|rendered|caller after', $output);
        },
        'exceptions clean every nested template buffer' => static function () use ($controller, $fixtureName, $capture, $expectSame, $expectException): void {
            $output = $capture(static function () use ($controller, $fixtureName, $expectSame, $expectException): void {
                echo 'caller|';
                $initialLevel = ob_get_level();
                $error = $expectException(LogicException::class, static fn () => $controller->show($fixtureName . '/nested-throws'));
                $expectSame('Nested template failure.', $error->getMessage());
                $expectSame($initialLevel, ob_get_level());
                echo 'after';
            });
            $expectSame('caller|after', $output);
        },
        'unclosed nested buffers fail without publishing partial HTML' => static function () use ($controller, $fixtureName, $capture, $expectSame, $expectException): void {
            $output = $capture(static function () use ($controller, $fixtureName, $expectSame, $expectException): void {
                echo 'caller|';
                $initialLevel = ob_get_level();
                $expectException(RuntimeException::class, static fn () => $controller->show($fixtureName . '/unbalanced'));
                $expectSame($initialLevel, ob_get_level());
                echo 'after';
            });
            $expectSame('caller|after', $output);
        },
        'balanced template buffers contribute their output normally' => static function () use ($controller, $fixtureName, $capture, $expectSame): void {
            $expectSame('before|nested|after', $capture(static fn () => $controller->show($fixtureName . '/balanced')));
        },
        'HomeController emits the original HTML regardless of parameters and query' => static function () use ($projectRoot, $capture, $expectSame): void {
            $html = file_get_contents($projectRoot . '/public/index.html');
            if ($html === false) {
                throw new RuntimeException('Could not read the original home HTML.');
            }
            $home = new HomeController();
            $expectSame($html, $capture(static fn () => $home->index([], [])));
            $expectSame($html, $capture(static fn () => $home->index(
                ['view' => '../composer.json'],
                ['view' => 'php://filter/resource=home', 'message' => '<script>unexpected</script>'],
            )));
        },
    ];

    foreach (['/', '/index.php'] as $target) {
        $tests['GET ' . $target . ' invokes HomeController and preserves the original HTML'] = static function () use ($projectRoot, $target, $capture, $expectSame): void {
            $router = new Router();
            $registerRoutes = require $projectRoot . '/config/routes.php';
            $registerRoutes($router);
            $route = $router->resolve('GET', $target . '?view=../composer.json&message=%3Cscript%3E');
            $expectSame(200, $route['status']);
            $handler = new ReflectionFunction($route['handler']);
            $expectSame(true, $handler->getClosureThis() instanceof HomeController);
            $expectSame('index', $handler->getName());
            $html = file_get_contents($projectRoot . '/public/index.html');
            if ($html === false) {
                throw new RuntimeException('Could not read the original home HTML.');
            }
            $expectSame($html, $capture(static fn () => ($route['handler'])($route['parameters'], $route['query'])));
        };
    }

    $escapeLink = $fixtureDirectory . '/escape.php';
    if (function_exists('symlink') && @symlink($projectRoot . '/public/index.html', $escapeLink)) {
        $createdFiles[] = $escapeLink;
        $tests['symlinks cannot escape the templates directory'] = static function () use ($controller, $fixtureName, $capture, $expectSame, $expectException): void {
            $expectSame('', $capture(static fn () => $expectException(
                RuntimeException::class,
                static fn () => $controller->show($fixtureName . '/escape'),
            )));
        };
    } else {
        $skipped[] = 'Symlink escape: the platform could not create the fixture symlink.';
    }

    foreach ($tests as $name => $test) {
        try {
            $test();
        } catch (Throwable $error) {
            $failures[] = $name . ': ' . $error->getMessage();
        }
    }
} catch (Throwable $error) {
    $failures[] = 'Fixture setup: ' . $error->getMessage();
} finally {
    foreach (array_reverse($createdFiles) as $path) {
        if (!@unlink($path)) {
            $failures[] = 'Fixture cleanup: could not remove ' . basename($path) . '.';
        }
    }
    foreach (array_reverse($createdDirectories) as $path) {
        if (!@rmdir($path)) {
            $failures[] = 'Fixture cleanup: could not remove directory ' . basename($path) . '.';
        }
    }
}

foreach ($skipped as $message) {
    printf("SKIP: %s\n", $message);
}

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    fprintf(STDERR, "Controller tests: %d failures across %d scenarios.\n", count($failures), count($tests));
    exit(1);
}

printf("Controller tests: %d scenarios passed; %d skipped.\n", count($tests), count($skipped));
