<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon;

use Behat\MinkExtension\Context\RawMinkContext;
use Soulcodex\Behat\Context\KernelAwareMinkContext;

abstract class Context extends RawMinkContext implements KernelAwareMinkContext
{
    use InteractWithKernelContext;
}