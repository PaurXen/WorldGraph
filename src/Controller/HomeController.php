<?php

declare(strict_types=1);

namespace WorldGraph\Controller;

final class HomeController extends BaseController
{
    public function index(array $parameters, array $query): void
    {
        $this->render('home');
    }
}
