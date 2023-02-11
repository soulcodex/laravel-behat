<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon;

use Behat\MinkExtension\Context\RawMinkContext;
use Soulcodex\Behat\Context\KernelAwareContext;

abstract class Context extends RawMinkContext implements KernelAwareContext
{
    use InteractWithKernelContext;
}