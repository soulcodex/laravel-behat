<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Context;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class KernelContextConfiguration implements Arrayable
{
    private function __construct(private string $bootstrapPath, private string $environmentFile)
    {
    }

    public static function fromKernel(HttpKernelInterface|Application $application): self
    {
        return new self(
            $application->bootstrapPath(),
            sprintf('.env.%s', $application->environment())
        );
    }

    public function bootstrapPath(): string
    {
        return $this->bootstrapPath;
    }

    public function environmentFile(): string
    {
        return $this->environmentFile;
    }

    public function toArray(): array
    {
        return [
            'kernel' => [
                'bootstrap_path' => $this->bootstrapPath(),
                'environment_path' => $this->environmentFile()
            ]
        ];
    }
}