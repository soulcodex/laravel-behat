<?php

namespace Soulcodex\Behat\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\HttpKernel\HttpKernelBrowser as Client;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelDriver extends BrowserKitDriver
{
    /**
     * Create a new KernelDriver.
     *
     * @param Application|HttpKernelInterface $app
     * @param string|null $baseUrl
     */
    public function __construct(Application|HttpKernelInterface $app, private ?string $baseUrl = null)
    {
        parent::__construct(new Client($app), $this->baseUrl);
    }

    public function reboot(Application|HttpKernelInterface $app): void
    {
        $this->__construct($app, $this->baseUrl);
    }
}
