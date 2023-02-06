<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon;

use Behat\Mink\Session;
use Behat\MinkExtension\Context\RawMinkContext;
use Illuminate\Contracts\Foundation\Application;
use Soulcodex\Behat\Context\KernelAwareContext;

abstract class Context extends RawMinkContext implements KernelAwareContext
{
    protected Application $app;

    public function reboot(Application $app): void
    {
        $this->app = $app;
    }

    public function session(string $sessionName): Session
    {
        return $this->getMink()->getSession($sessionName);
    }
}