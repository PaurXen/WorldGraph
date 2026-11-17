<?php

declare(strict_types=1);

use WorldGraph\Controller\HomeController;
use WorldGraph\Core\Http\Router;

return static function (Router $router): void {
    $home = new HomeController();

    $router->add('GET', '/', [$home, 'index']);
    $router->add('GET', '/index.php', [$home, 'index']);
    $router->add('GET', '/index.html', [$home, 'index']);
};
