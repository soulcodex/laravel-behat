<?php

namespace Soulcodex\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Symfony\Component\HttpKernel\HttpKernelBrowser as Client;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelDriver extends BrowserKitDriver
{
    /**
     * Create a new KernelDriver.
     *
     * @param HttpKernelInterface $app
     * @param string|null $baseUrl
     */
    public function __construct(HttpKernelInterface $app, private ?string $baseUrl = null)
    {
        parent::__construct(new Client($app), $this->baseUrl);
    }

    /**
     * Refresh the driver.
     *
     * @param HttpKernelInterface $app
     * @return KernelDriver
     */
    public function reboot(HttpKernelInterface $app): KernelDriver
    {
        return self::__construct($app, $this->baseUrl);
    }
}
