<?php

namespace Inbenta\Domain\Shared\Validation;

class StringValidation
{
    private const VALUE_TOO_LONG = "Value is too long";

    public static function length(string $value, int $length): void
    {
        if (mb_strlen($value) > $length) {
            throw new \InvalidArgumentException(self::VALUE_TOO_LONG);
        }
    }
}
