<?php

declare(strict_types=1);

$expect = static function (bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$createdFiles = [];
$createdDirectories = [];
$process = null;
$failures = [];
$tests = [];

try {
    $projectRoot = dirname(__DIR__, 2);
    $cacheDirectory = realpath($projectRoot . '/storage/cache');
    $resolvedProject = realpath($projectRoot);
    if ($cacheDirectory === false || $resolvedProject === false
        || !str_starts_with($cacheDirectory, $resolvedProject . DIRECTORY_SEPARATOR)
    ) {
        throw new RuntimeException('The test cache must be inside the project.');
    }
    $fixtureDirectory = $cacheDirectory . '/_error_http_' . bin2hex(random_bytes(12));
    foreach (['', '/public', '/config', '/vendor', '/src', '/src/Core', '/src/Core/Http', '/src/Controller', '/templates', '/templates/errors', '/templates/layouts', '/templates/partials'] as $directory) {
        $path = $fixtureDirectory . $directory;
        if (!mkdir($path, 0700)) {
            throw new RuntimeException('Could not create a private test fixture directory.');
        }
        $createdDirectories[] = $path;
    }

    $write = static function (string $relativePath, string $contents) use ($fixtureDirectory, &$createdFiles): void {
        $path = $fixtureDirectory . '/' . $relativePath;
        $file = fopen($path, 'xb');
        if ($file === false) {
            throw new RuntimeException('Could not create a private fixture file.');
        }
        $createdFiles[] = $path;
        try {
            if (fwrite($file, $contents) !== strlen($contents)) {
                throw new RuntimeException('Could not write the complete private fixture.');
            }
        } finally {
            fclose($file);
        }
    };

    foreach ([
        'public/index.php',
        'src/Core/Http/Router.php',
        'src/Controller/BaseController.php',
        'src/Controller/ErrorController.php',
        'templates/errors/http.php',
        'templates/layouts/main.php',
        'templates/partials/header.php',
        'templates/partials/navigation.php',
        'templates/partials/footer.php',
    ] as $sourceFile) {
        $contents = file_get_contents($projectRoot . '/' . $sourceFile);
        if ($contents === false) {
            throw new RuntimeException('Could not read the application source fixture.');
        }
        $write($sourceFile, $contents);
    }
    $write('vendor/autoload.php', <<<'PHP'
<?php
spl_autoload_register(static function (string $class): void {
    $prefix = 'WorldGraph\\';
    if (str_starts_with($class, $prefix)) {
        require dirname(__DIR__) . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    }
});
PHP);
    $write('config/routes.php', <<<'PHP'
<?php
return static function (WorldGraph\Core\Http\Router $router): void {
    $router->add('GET', '/failure', static function (): void {
        http_response_code(202);
        header('Location: /private-location-marker');
        header('Allow: TRACE');
        header('Content-Length: 999999');
        header('Content-Type: application/json');
        header('X-Fixture-Secret: private-header-marker');
        header('Set-Cookie: fixture=private-cookie-marker');
        echo 'private-output-marker';
        ob_start();
        echo 'private-nested-output-marker';
        throw new RuntimeException('private-exception-marker ' . ($_SERVER['QUERY_STRING'] ?? ''));
    });
    $router->add('GET', '/error', static function (): void {
        throw new Error('private-error-marker');
    });
};
PHP);
    $write('application.log', '');
    $write('server.log', '');

    $socket = stream_socket_server('tcp://127.0.0.1:0', $socketError, $socketMessage);
    if ($socket === false) {
        throw new RuntimeException('Could not reserve a loopback test port.');
    }
    $address = stream_socket_get_name($socket, false);
    fclose($socket);
    if ($address === false) {
        throw new RuntimeException('Could not read the loopback test port.');
    }
    $process = proc_open([
        PHP_BINARY,
        '-d', 'display_errors=0',
        '-d', 'display_startup_errors=0',
        '-d', 'log_errors=1',
        '-d', 'error_log=' . $fixtureDirectory . '/application.log',
        '-S', $address,
        '-t', $fixtureDirectory . '/public',
        $fixtureDirectory . '/public/index.php',
    ], [
        0 => ['pipe', 'r'],
        1 => ['file', $fixtureDirectory . '/server.log', 'ab'],
        2 => ['file', $fixtureDirectory . '/server.log', 'ab'],
    ], $pipes, $fixtureDirectory);
    if (!is_resource($process)) {
        throw new RuntimeException('Could not start the isolated PHP test server.');
    }
    fclose($pipes[0]);
    $ready = false;
    $deadline = microtime(true) + 5.0;
    do {
        $probe = @stream_socket_client('tcp://' . $address, $probeError, $probeMessage, 0.1);
        if (is_resource($probe)) {
            fclose($probe);
            $ready = true;
            break;
        }
        usleep(25000);
    } while (microtime(true) < $deadline && proc_get_status($process)['running']);
    $expect($ready, 'The isolated PHP test server did not become ready.');

    $request = static function (string $method, string $target) use ($address): array {
        $stream = @fopen('http://' . $address . $target, 'rb', false, stream_context_create(['http' => [
            'method' => $method,
            'ignore_errors' => true,
            'follow_location' => 0,
            'timeout' => 3.0,
            'header' => "Connection: close\r\nAccept-Encoding: identity",
        ]]));
        if ($stream === false) {
            throw new RuntimeException('Could not open an isolated HTTP response.');
        }
        try {
            $body = stream_get_contents($stream);
            $metadata = stream_get_meta_data($stream);
        } finally {
            fclose($stream);
        }
        if ($body === false || ($metadata['timed_out'] ?? false)) {
            throw new RuntimeException('The isolated HTTP response was incomplete.');
        }
        $status = null;
        $headers = [];
        foreach ($metadata['wrapper_data'] ?? [] as $line) {
            if (preg_match('~^HTTP/\d+(?:\.\d+)? ([0-9]{3})(?:\s|$)~', $line, $matches) === 1) {
                $status = (int) $matches[1];
                $headers = [];
            } elseif (str_contains($line, ':')) {
                [$name, $value] = explode(':', $line, 2);
                $headers[strtolower(trim($name))][] = trim($value);
            }
        }
        return ['status' => $status, 'headers' => $headers, 'body' => $body];
    };

    $expectFailure = static function (string $method, string $target, string $type) use ($request, $expect): void {
        $response = $request($method, $target);
        $expect($response['status'] === 500, 'Expected HTTP 500, got ' . ($response['status'] ?? 'no status') . '.');
        $expect(($response['headers']['content-type'] ?? []) === [$type . '; charset=utf-8'], 'Expected a single safe UTF-8 Content-Type.');
        $expect(($response['headers']['cache-control'] ?? []) === ['no-store'], 'Failed responses must not be cached.');
        foreach (['location', 'allow', 'content-length', 'x-fixture-secret', 'set-cookie'] as $name) {
            $expect(!isset($response['headers'][$name]), 'A failed response retained the ' . $name . ' header.');
        }
        foreach (['private-', 'Stack trace:', 'Fatal error:', 'Uncaught', $target, '/src/Controller/', '/templates/'] as $marker) {
            $expect(!str_contains($response['body'], $marker), 'A failed response exposed diagnostic, request or partial content.');
        }
        if ($method === 'HEAD') {
            $expect($response['body'] === '', 'HEAD must suppress the failed response body.');
        } elseif ($type === 'text/plain') {
            $expect(trim($response['body']) === '500 — Błąd serwera. Spróbuj ponownie później.', 'Expected the fixed emergency response.');
        } else {
            $expect(str_contains($response['body'], 'Wystąpił błąd serwera. Spróbuj ponownie później.'), 'Expected the generic 500 page.');
            $expect(str_contains($response['body'], '<main id="main">'), 'The normal 500 page must use the shared layout.');
            $expect(str_contains($response['body'], 'Powrót do strony głównej'), 'The normal 500 page must offer a safe home link.');
        }
    };

    $withFixture = static function (string $relativePath, ?string $contents, callable $operation) use ($fixtureDirectory): void {
        $path = $fixtureDirectory . '/' . $relativePath;
        $original = file_get_contents($path);
        if ($original === false) {
            throw new RuntimeException('Could not preserve the owned fixture file.');
        }
        try {
            if ($contents === null) {
                if (!unlink($path)) {
                    throw new RuntimeException('Could not remove the owned fixture file.');
                }
            } elseif (file_put_contents($path, $contents) !== strlen($contents)) {
                throw new RuntimeException('Could not modify the owned fixture file.');
            }
            $operation();
        } finally {
            if (file_put_contents($path, $original) !== strlen($original)) {
                throw new RuntimeException('Could not restore the owned fixture file.');
            }
        }
    };

    $tests = [
        'the configured PHP process hides diagnostics and enables logging' => static function () use ($expect): void {
            foreach (['display_errors', 'display_startup_errors'] as $setting) {
                $expect(in_array(strtolower((string) ini_get($setting)), ['', '0', 'off'], true), $setting . ' must be disabled by PHP configuration.');
            }
            $expect(in_array(strtolower((string) ini_get('log_errors')), ['1', 'on'], true), 'log_errors must be enabled by PHP configuration.');
        },
        'exceptions discard nested output and stale headers' => static fn () => $expectFailure('GET', '/failure?secret=private-query-marker', 'text/html'),
        'HEAD suppresses the rendered 500 response' => static fn () => $expectFailure('HEAD', '/failure?secret=private-head-marker', 'text/html'),
        'Error objects receive the same safe 500 handling' => static fn () => $expectFailure('GET', '/error', 'text/html'),
        'route registration failures are handled before dispatch' => static fn () => $withFixture('config/routes.php', '<?php echo "private-bootstrap-output"; throw new LogicException("private-bootstrap-marker");', static fn () => $expectFailure('GET', '/bootstrap', 'text/html')),
        'a throwing error view falls back without a second partial page' => static fn () => $withFixture('templates/errors/http.php', '<?php echo "private-error-view-output"; ob_start(); throw new LogicException("private-error-view-marker");', static fn () => $expectFailure('GET', '/missing', 'text/plain')),
        'a missing error view receives the emergency 500 response' => static fn () => $withFixture('templates/errors/http.php', null, static fn () => $expectFailure('GET', '/missing', 'text/plain')),
        'a missing layout receives the emergency 500 response' => static fn () => $withFixture('templates/layouts/main.php', null, static fn () => $expectFailure('GET', '/failure', 'text/plain')),
        'HEAD suppresses the emergency response' => static fn () => $withFixture('templates/layouts/main.php', null, static fn () => $expectFailure('HEAD', '/failure', 'text/plain')),
        'a missing Composer autoloader receives the emergency response' => static fn () => $withFixture('vendor/autoload.php', null, static fn () => $expectFailure('GET', '/bootstrap', 'text/plain')),
        'a throwing Composer autoloader discards its partial output' => static fn () => $withFixture('vendor/autoload.php', '<?php echo "private-autoload-output"; throw new LogicException("private-autoload-marker");', static fn () => $expectFailure('GET', '/bootstrap', 'text/plain')),
        'technical logs contain failure metadata without exception or request content' => static function () use ($fixtureDirectory, $expect): void {
            $log = file_get_contents($fixtureDirectory . '/application.log');
            $expect($log !== false && str_contains($log, 'WorldGraph request failure:'), 'Expected technical failure metadata in the private log.');
            $expect(str_contains($log, 'RuntimeException') && str_contains($log, 'Error'), 'Both exceptions and Error objects must be logged.');
            foreach (['private-', 'Stack trace:', 'secret=', 'QUERY_STRING'] as $marker) {
                $expect(!str_contains($log, $marker), 'The technical log exposed exception or request data.');
            }
        },
    ];
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
    if (is_resource($process)) {
        proc_terminate($process);
        proc_close($process);
    }
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

if ($failures !== []) {
    fwrite(STDERR, implode(PHP_EOL, $failures) . PHP_EOL);
    fprintf(STDERR, "Error handling HTTP tests: %d failures across %d scenarios.\n", count($failures), count($tests));
    exit(1);
}

printf("Error handling HTTP tests: %d scenarios passed.\n", count($tests));
