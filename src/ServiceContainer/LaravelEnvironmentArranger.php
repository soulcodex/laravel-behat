<?php

namespace Soulcodex\Behat\ServiceContainer;

use Illuminate\Contracts\Foundation\Application;
use RuntimeException;
use Soulcodex\Behat\Context\KernelContextConfiguration;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class LaravelEnvironmentArranger
{
    public function __construct(private string $basePath, private string $environmentFile)
    {
    }

    public function basePath(): string
    {
        return $this->basePath;
    }

    public function environmentFile(): string
    {
        return $this->environmentFile;
    }

    public function boot(array $config): Application|HttpKernelInterface
    {
        $bootstrapPath = $this->basePath() . $config['kernel']['bootstrap_path'];

        $this->assertBootstrapFileExists($bootstrapPath);

        $app = require $bootstrapPath;

        $app->loadEnvironmentFrom($this->environmentFile());

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app->make('Illuminate\Http\Request')->capture();

        $app->bind(
            KernelContextConfiguration::class,
            fn() => KernelContextConfiguration::fromConfigWithBasePath(
                $config,
                $app->basePath()
            )
        );

        return $app;
    }

    private function assertBootstrapFileExists(string $bootstrapPath): void
    {
        if (!file_exists($bootstrapPath)) {
            throw new RuntimeException('Could not locate the path to the Laravel bootstrap file.');
        }
    }
}