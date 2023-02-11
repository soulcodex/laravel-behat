<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon;

use Behat\MinkExtension\Context\MinkContext;
use Soulcodex\Behat\Context\KernelAwareContext;

final class RootContext extends MinkContext implements KernelAwareContext
{
    use InteractWithKernelContext;
}