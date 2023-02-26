<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon\Traits;

use Illuminate\Contracts\Foundation\Application;

trait InteractWithKernel
{
    protected Application $app;

    public function reboot(Application $app): void
    {
        $this->app = $app;
    }

    protected function container(): Application
    {
        return $this->app;
    }
}