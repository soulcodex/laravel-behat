<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Mink\Exception;

use Exception;

final class UnmarshalJsonBody extends Exception
{
    public static function unmarshalBody(string $body): self
    {
        return new self(message: sprintf('Unable to unmarshal the body to json. Body: %s', $body));
    }
}