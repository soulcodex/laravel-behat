<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Illuminate\Contracts\Foundation\Application;

interface KernelAwareContext extends Context
{
    /**
     * Reboot application on each scenario.
     *
     * @param Application $application
     * @return void
     */
    public function reboot(Application $application): void;

    /**
     * Get current Mink session driver.
     *
     * @param string $sessionName
     * @return Session
     */
    public function driverSession(string $sessionName): Session;
}