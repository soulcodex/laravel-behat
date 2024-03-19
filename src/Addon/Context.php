<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon;

use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Soulcodex\Behat\Addon\Traits\InteractWithKernel;
use Soulcodex\Behat\Addon\Traits\InteractWithMink;
use Soulcodex\Behat\Context\KernelAwareMinkContext;

/**
 * @mixin MinkContext
 */
abstract class Context extends RawMinkContext implements KernelAwareMinkContext
{
    use InteractWithKernel, InteractWithMink;
}