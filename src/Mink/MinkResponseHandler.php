<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Mink;

final class MinkResponseHandler
{
    public function __construct(private MinkHandler $handler)
    {
    }

    public function response(): string
    {
        return $this->handler->plainResponse();
    }
}