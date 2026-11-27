<?php

declare(strict_types=1);

namespace WorldGraph\Core\Http;

use Closure;
use InvalidArgumentException;

final class Router
{
    /** @var array<string, array<array-key, Closure>> */
    private array $staticRoutes = [];

    /** @var array<string, array<array-key, Closure>> */
    private array $regexRoutes = [];

    public function add(string $method, string $path, callable $handler): void
    {
        if (!$this->isValidMethod($method) || !$this->isValidTarget($path) || str_contains($path, '?')) {
            throw new InvalidArgumentException('Invalid static route method or path.');
        }

        if (isset($this->staticRoutes[$path][$method])) {
            throw new InvalidArgumentException('Static route already registered for this method.');
        }

        $this->staticRoutes[$path][$method] = Closure::fromCallable($handler);
    }

    public function addRegex(string $method, string $pattern, callable $handler): void
    {
        if (!$this->isValidMethod($method) || @preg_match($pattern, '') === false) {
            throw new InvalidArgumentException('Invalid regex route method or pattern.');
        }

        if (isset($this->regexRoutes[$pattern][$method])) {
            throw new InvalidArgumentException('Regex route already registered for this method.');
        }

        $this->regexRoutes[$pattern][$method] = Closure::fromCallable($handler);
    }

    /**
     * @return array{
     *     status: int,
     *     handler: Closure|null,
     *     parameters: array<string, string|null>,
     *     query: array<array-key, mixed>,
     *     allowedMethods: list<string>
     * }
     */
    public function resolve(string $method, string $requestTarget): array
    {
        $result = [
            'status' => 404,
            'handler' => null,
            'parameters' => [],
            'query' => [],
            'allowedMethods' => [],
        ];

        if (!$this->isValidMethod($method) || !$this->isValidTarget($requestTarget)) {
            $result['status'] = 400;

            return $result;
        }

        [$path, $queryString] = array_pad(explode('?', $requestTarget, 2), 2, '');
        parse_str($queryString, $result['query']);

        $candidates = [];
        if (isset($this->staticRoutes[$path])) {
            $candidates[] = ['handlers' => $this->staticRoutes[$path], 'parameters' => []];
        } else {
            foreach ($this->regexRoutes as $pattern => $handlers) {
                $matched = preg_match($pattern, $path, $captures, PREG_UNMATCHED_AS_NULL);
                if ($matched !== 1 || $captures[0] !== $path) {
                    continue;
                }

                $parameters = [];
                foreach ($captures as $name => $value) {
                    if (is_string($name)) {
                        $parameters[$name] = $value === null ? null : rawurldecode($value);
                    }
                }

                $candidates[] = ['handlers' => $handlers, 'parameters' => $parameters];
            }
        }

        $methodsToTry = $method === 'HEAD' ? ['HEAD', 'GET'] : [$method];
        foreach ($methodsToTry as $candidateMethod) {
            foreach ($candidates as $candidate) {
                if (isset($candidate['handlers'][$candidateMethod])) {
                    $result['status'] = 200;
                    $result['handler'] = $candidate['handlers'][$candidateMethod];
                    $result['parameters'] = $candidate['parameters'];

                    return $result;
                }
            }
        }

        if ($candidates !== []) {
            $result['status'] = 405;
            foreach ($candidates as $candidate) {
                foreach (array_keys($candidate['handlers']) as $allowedMethod) {
                    $result['allowedMethods'][] = (string) $allowedMethod;
                    if ($allowedMethod === 'GET') {
                        $result['allowedMethods'][] = 'HEAD';
                    }
                }
            }
            $result['allowedMethods'] = array_values(array_unique($result['allowedMethods']));
            sort($result['allowedMethods'], SORT_STRING);
        }

        return $result;
    }

    private function isValidMethod(string $method): bool
    {
        return preg_match('/\A[!#$%&\'*+.^_\x60|~0-9A-Za-z-]+\z/', $method) === 1;
    }

    private function isValidTarget(string $target): bool
    {
        return str_starts_with($target, '/')
            && !str_contains($target, '#')
            && !str_contains($target, '\\')
            && preg_match('/[\x00-\x20\x7f]|%(?![0-9A-Fa-f]{2})/', $target) === 0;
    }
}
