<?php

declare(strict_types=1);

namespace WorldGraph\Controller;

use InvalidArgumentException;
use RuntimeException;

abstract class BaseController
{
    protected function render(string $view, array $data = [], ?string $layout = null): void
    {
        $templateFile = $this->resolveTemplate($view);
        $layoutFile = $layout === null ? null : $this->resolveTemplate($layout);

        $content = $this->captureTemplate($templateFile, $data);
        if ($layoutFile !== null) {
            $content = $this->captureTemplate($layoutFile, $data, $content);
        }

        echo $content;
    }

    private function resolveTemplate(string $name): string
    {
        if (preg_match('~\A[A-Za-z0-9_-]+(?:/[A-Za-z0-9_-]+)*\z~', $name) !== 1) {
            throw new InvalidArgumentException('Invalid template name.');
        }

        $templatesDirectory = realpath(dirname(__DIR__, 2) . '/templates');
        if ($templatesDirectory === false) {
            throw new RuntimeException('Template directory is not available.');
        }

        $templateFile = realpath($templatesDirectory . '/' . $name . '.php');
        if ($templateFile === false
            || !str_starts_with($templateFile, $templatesDirectory . DIRECTORY_SEPARATOR)
            || !is_file($templateFile)
            || !is_readable($templateFile)
        ) {
            throw new RuntimeException('Template is not available.');
        }

        return $templateFile;
    }

    private function captureTemplate(string $templateFile, array $data, ?string $content = null): string
    {
        $bufferLevel = ob_get_level();
        ob_start();
        try {
            (static function (string $templateFile, array $data, ?string $content): void {
                require $templateFile;
            })($templateFile, $data, $content);

            if (ob_get_level() !== $bufferLevel + 1) {
                throw new RuntimeException('Template did not preserve output buffering.');
            }

            $output = ob_get_contents();
            if ($output === false) {
                throw new RuntimeException('Could not read template output.');
            }
        } finally {
            while (ob_get_level() > $bufferLevel) {
                if (!ob_end_clean()) {
                    throw new RuntimeException('Could not close template output buffer.');
                }
            }
        }

        return $output;
    }
}
