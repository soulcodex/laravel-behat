<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon\Traits;

use PHPUnit\Framework\Assert;

/**
 * @mixin Assert
 */
trait InteractWithAssertion
{
    public function __call(string $name, array $arguments)
    {
        Assert::$name(...$arguments);
    }
}