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
    private const MINK_SESSION_NAME = 'laravel';

    private Context $context;

    public function __construct(
        private HttpKernelInterface|Application $app,
        private KernelContextConfiguration      $kernelConfiguration
    )
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
        if ($this->context instanceof KernelAwareMinkContext) {
            $this->context->reboot($this->app);
        }
    }

    public function rebootKernel()
    {
        if ($this->context instanceof KernelAwareMinkContext) {
            $laravel = new LaravelEnvironmentArranger(
                $this->kernelConfiguration->basePath(),
                $this->kernelConfiguration->environmentFile()
            );

            $this->app = $laravel->boot($this->kernelConfiguration->toArray());

            $this->context->minkSession(self::MINK_SESSION_NAME)
                ->getDriver()
                ->reboot($this->app);

            $this->setAppOnContext();
        }
    }
}