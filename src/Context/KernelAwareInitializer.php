<?php

namespace Soulcodex\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Illuminate\Contracts\Foundation\Application;
use Soulcodex\Behat\ServiceContainer\LaravelEnvironmentArranger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class KernelAwareInitializer implements EventSubscriberInterface, ContextInitializer
{
    private Context $context;

    public function __construct(private HttpKernelInterface|Application $app)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScenarioTested::AFTER => ['rebootKernel', -15]
        ];
    }

    public function initializeContext(Context $context): void
    {
        $this->context = $context;
        $this->setAppOnContext();
    }

    /**
     * Set the app kernel to the feature context.
     */
    private function setAppOnContext()
    {
        if ($this->context instanceof KernelAwareContext) {
            $this->context->reboot($this->app);
        }
    }

    /**
     * After each scenario, reboot the kernel.
     */
    public function rebootKernel()
    {
        if ($this->context instanceof KernelAwareContext) {
            $kernelContextConfiguration = KernelContextConfiguration::fromKernel($this->app);

            $laravel = new LaravelEnvironmentArranger(
                $this->app->basePath(),
                sprintf('.env.%s', $this->app->environment())
            );

            $this->context->driverSession('laravel')
                ->getDriver()
                ->reboot($this->app = $laravel->boot($kernelContextConfiguration->toArray()));
        }
    }
}