<?php

declare(strict_types=1);

use WorldGraph\Core\Http\Router;

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
}
