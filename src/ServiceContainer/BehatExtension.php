<?php

namespace Soulcodex\Behat\ServiceContainer;

use Behat\MinkExtension\ServiceContainer\MinkExtension;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Illuminate\Contracts\Foundation\Application;
use Soulcodex\Behat\Context\KernelContextConfiguration;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Soulcodex\Behat\Context\Argument\LaravelArgumentResolver;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;

class BehatExtension implements Extension
{
    public function getConfigKey(): string
    {
        return 'laravel';
    }

    public function initialize(ExtensionManager $extensionManager): void
    {
        $minkExtension = $extensionManager->getExtension('mink');

        if ($minkExtension instanceof MinkExtension) {
            $minkExtension->registerDriverFactory(new LaravelFactory());
        }
    }

    public function process(ContainerBuilder $container): void
    {
    }

    public function configure(ArrayNodeDefinition $builder): void
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('kernel')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('bootstrap_path')->defaultValue('/bootstrap/app.php')->end()
                        ->scalarNode('environment_path')->defaultValue('.env.behat')->end()
                    ->end()
                ->end()
            ->end();
    }

    public function load(ContainerBuilder $container, array $config): void
    {
        $app = $this->loadLaravel($container, $config);

        $this->loadKernelConfiguration($container, $app, $config);
        $this->loadInitializer($container, $app);
        $this->loadLaravelArgumentResolver($container);
    }

    private function loadLaravel(ContainerBuilder $container, array $config): Application
    {
        $laravel = new LaravelEnvironmentArranger(
            $container->getParameter('paths.base'),
            $config['kernel']['environment_path']
        );

        $container->set('laravel.app', $app = $laravel->boot($config));

        return $app;
    }

    private function loadInitializer(ContainerBuilder $container, Application $app): void
    {
        $definition = new Definition(
            'Soulcodex\Behat\Context\KernelAwareInitializer',
            [$app, new Reference('laravel.behat_extension.kernel_context_config')]
        );

        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, ['priority' => 0]);
        $definition->addTag(ContextExtension::INITIALIZER_TAG, ['priority' => 0]);

        $container->setDefinition('laravel.initializer', $definition);
    }

    private function loadLaravelArgumentResolver(ContainerBuilder $container): void
    {
        $definition = new Definition(LaravelArgumentResolver::class, [
            new Reference('laravel.app')
        ]);

        $definition->addTag(ContextExtension::ARGUMENT_RESOLVER_TAG, ['priority' => 0]);
        $container->setDefinition('laravel.context.argument.service_resolver', $definition);
    }

    private function loadKernelConfiguration(ContainerBuilder $container, Application $app, array $config)
    {
        $definition = new Definition(
            'Soulcodex\Behat\Context\KernelContextConfiguration',
            [$config, $app->basePath()]
        );

        $definition->setFactory([KernelContextConfiguration::class, 'fromConfigWithBasePath']);

        $container->setDefinition('laravel.behat_extension.kernel_context_config', $definition);
    }
}
