<?php

namespace Soulcodex\Behat\ServiceContainer;

use Behat\MinkExtension\ServiceContainer\Driver\DriverFactory;
use RuntimeException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class LaravelFactory implements DriverFactory
{
    public function getDriverName(): string
    {
        return 'laravel';
    }

    public function supportsJavascript(): bool
    {
        return false;
    }

    public function configure(ArrayNodeDefinition $builder)
    {
    }

    public function buildDriver(array $config): Definition
    {
        $this->assertBrowserkitIsAvailable();

        return new Definition('Soulcodex\Behat\Driver\KernelDriver', [
            new Reference('laravel.app'),
            '%mink.base_url%'
        ]);
    }

    private function assertBrowserKitIsAvailable()
    {
        if (!class_exists('Behat\Mink\Driver\BrowserKitDriver')) {
            throw new RuntimeException(
                'Install MinkBrowserKitDriver in order to use the laravel driver.'
            );
        }
    }
}

