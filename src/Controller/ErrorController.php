<?php

declare(strict_types=1);

namespace WorldGraph\Controller;

use InvalidArgumentException;

final class ErrorController extends BaseController
{
    private const array MESSAGES = [
        400 => ['Nieprawidłowe żądanie', 'Żądanie ma nieprawidłowy format.'],
        404 => ['Nie znaleziono strony', 'Nie znaleziono strony pod tym adresem.'],
        405 => ['Niedozwolona metoda', 'Ta metoda żądania jest niedozwolona.'],
        500 => ['Błąd serwera', 'Wystąpił błąd serwera. Spróbuj ponownie później.'],
    ];

    public function show(int $status): void
    {
        if (!isset(self::MESSAGES[$status])) {
            throw new InvalidArgumentException('Unsupported error status.');
        }

        [$heading, $message] = self::MESSAGES[$status];
        http_response_code($status);
        $this->render('errors/http', [
            'status' => $status,
            'title' => $status . ' — ' . $heading . ' | WorldGraph',
            'description' => $message,
            'heading' => $heading,
            'message' => $message,
        ], 'layouts/main');
    }
}
