<?php

namespace Soulcodex\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Illuminate\Contracts\Container\BindingResolutionException;
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

    private function setAppOnContext()
    {
        if ($this->context instanceof KernelAwareContext) {
            $this->context->reboot($this->app);
        }
    }

    public function rebootKernel()
    {
        if ($this->context instanceof KernelAwareContext) {
            $kernelConfig = $this->kernelConfig();
            $this->app->flush();

            $laravel = new LaravelEnvironmentArranger(
                $kernelConfig->basePath(),
                $kernelConfig->environmentFile()
            );

            $this->context->session('laravel')
                ->getDriver()
                ->reboot($this->app = $laravel->boot($kernelConfig->toArray()));
        }
    }

    private function kernelConfig(): KernelContextConfiguration
    {
        try {
            return $this->app->make(KernelContextConfiguration::class);
        } catch (BindingResolutionException) {
            return KernelContextConfiguration::fromKernel($this->app);
        }
    }
}