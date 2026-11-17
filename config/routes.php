<?php

declare(strict_types=1);

use WorldGraph\Core\Http\Router;

return static function (Router $router): void {
    $home = static function (array $parameters, array $query): void {
        readfile(dirname(__DIR__) . '/public/index.html');
    };

    $router->add('GET', '/', $home);
    $router->add('GET', '/index.php', $home);
};
