<?php

declare(strict_types=1);

namespace WorldGraph\Controller;

final class HomeController extends BaseController
{
    public function index(array $parameters, array $query): void
    {
        $this->render('home', [
            'title' => 'WorldGraph — wszystko jest połączone',
            'description' => 'WorldGraph — pomysł na uporządkowaną przestrzeń dla postaci, miejsc i historii twojego fikcyjnego świata. Poznaj demonstracyjny szablon projektu.',
        ], 'layouts/main');
    }
}
