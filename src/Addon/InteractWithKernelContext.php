<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon;
use Behat\Mink\Session;
use Illuminate\Contracts\Foundation\Application;

trait InteractWithKernelContext
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