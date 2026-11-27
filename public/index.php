<?php

declare(strict_types=1);

use WorldGraph\Controller\ErrorController;
use WorldGraph\Core\Http\Router;

$isHead = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'HEAD';

$captureResponse = static function (callable $operation): string {
    $bufferLevel = ob_get_level();
    ob_start();

    try {
        $operation();
        if (ob_get_level() !== $bufferLevel + 1) {
            throw new RuntimeException('Response did not preserve output buffering.');
        }

        $body = ob_get_contents();
        if ($body === false) {
            throw new RuntimeException('Could not read response output.');
        }

        return $body;
    } finally {
        while (ob_get_level() > $bufferLevel) {
            if (!ob_end_clean()) {
                throw new RuntimeException('Could not close response output buffer.');
            }
        }
    }
};

$logFailure = static function (Throwable $error): void {
    $type = str_replace(["\r", "\n", "\0"], '?', $error::class);
    $file = str_replace(["\r", "\n", "\0"], '?', basename($error->getFile()));
    error_log(sprintf('WorldGraph request failure: %s in %s:%d', $type, $file, $error->getLine()));
};

try {
    $body = $captureResponse(static function (): void {
        require dirname(__DIR__) . '/vendor/autoload.php';

        $router = new Router();
        $registerRoutes = require dirname(__DIR__) . '/config/routes.php';
        $registerRoutes($router);

        $route = $router->resolve($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');

        http_response_code($route['status']);
        header('Content-Type: text/html; charset=utf-8');

        if ($route['allowedMethods'] !== []) {
            header('Allow: ' . implode(', ', $route['allowedMethods']));
        }

        if ($route['handler'] !== null) {
            ($route['handler'])($route['parameters'], $route['query']);
        } else {
            (new ErrorController())->show($route['status']);
        }
    });
} catch (Throwable $error) {
    header_remove();
    http_response_code(500);
    header('Cache-Control: no-store');
    $logFailure($error);

    try {
        $body = $captureResponse(static function (): void {
            header('Content-Type: text/html; charset=utf-8');
            (new ErrorController())->show(500);
        });
    } catch (Throwable $renderError) {
        header_remove();
        http_response_code(500);
        header('Cache-Control: no-store');
        header('Content-Type: text/plain; charset=utf-8');
        $logFailure($renderError);
        $body = '500 — Błąd serwera. Spróbuj ponownie później.';
    }
}

if (!$isHead) {
    echo $body;
}
