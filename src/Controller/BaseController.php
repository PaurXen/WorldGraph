<?php

declare(strict_types=1);

namespace WorldGraph\Controller;

use InvalidArgumentException;
use RuntimeException;

abstract class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        if (preg_match('~\A[A-Za-z0-9_-]+(?:/[A-Za-z0-9_-]+)*\z~', $view) !== 1) {
            throw new InvalidArgumentException('Invalid view name.');
        }

        $templatesDirectory = realpath(dirname(__DIR__, 2) . '/templates');
        if ($templatesDirectory === false) {
            throw new RuntimeException('Template directory is not available.');
        }

        $templateFile = realpath($templatesDirectory . '/' . $view . '.php');
        if ($templateFile === false
            || !str_starts_with($templateFile, $templatesDirectory . DIRECTORY_SEPARATOR)
            || !is_file($templateFile)
            || !is_readable($templateFile)
        ) {
            throw new RuntimeException('View is not available.');
        }

        $bufferLevel = ob_get_level();
        ob_start();
        try {
            (static function (string $templateFile, array $data): void {
                require $templateFile;
            })($templateFile, $data);

            if (ob_get_level() !== $bufferLevel + 1) {
                throw new RuntimeException('View did not preserve output buffering.');
            }

            $output = ob_get_contents();
            if ($output === false) {
                throw new RuntimeException('Could not read view output.');
            }
        } finally {
            while (ob_get_level() > $bufferLevel) {
                if (!ob_end_clean()) {
                    throw new RuntimeException('Could not close view output buffer.');
                }
            }
        }

        echo $output;
    }
}
