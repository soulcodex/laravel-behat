<?php

namespace Soulcodex\Behat\Context\Argument;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use ReflectionClass;
use Behat\Behat\Context\Argument\ArgumentResolver;

class LaravelArgumentResolver implements ArgumentResolver
{
    public function __construct(private Application $app)
    {
    }

    public function resolveArguments(ReflectionClass $classReflection, array $arguments): array
    {
        $resolvedArguments = [];

        foreach ($arguments as $key => $argument) {
            $resolvedArguments[$key] = !empty($argument)
                ? $this->resolveArgument($argument)
                : null;
        }

        return $resolvedArguments;
    }

    private function resolveArgument(string $argument): mixed
    {
        if (!empty($argument) && !str_starts_with($argument, '@')) {
            return $argument;
        }

        if (!empty($argument) && str_starts_with($argument, '@')) {
            return $this->app->make(substr($argument, 1));
        }

        throw new InvalidArgumentException(
            sprintf('Unable to resolve argument / service <%s>', $argument)
        );
    }
}
