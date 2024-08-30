<?php

declare(strict_types=1);

namespace App\Utils;

use App\Exception\InvalidStringValueException;
use App\Exception\InvalidIntValueException;

class ConvertTo
{
    /**
     * Validate and convert a value to a string if it is scalar.
     *
     * @param mixed $value
     * @param string $key
     * @return string
     * @throws InvalidStringValueException
     */
    public static function string(mixed $value, string $key): string
    {
        if (isset($value) && is_scalar($value)) {
            return strval($value);
        }

        throw new InvalidStringValueException($key);
    }

    /**
     * Validate and convert a value to an integer if it is scalar.
     *
     * @param mixed $value
     * @param string $key
     * @return int
     * @throws InvalidIntValueException
     */
    public static function int(mixed $value, string $key): int
    {
        if (isset($value) && is_scalar($value)) {
            return intval($value);
        }

        throw new InvalidIntValueException($key);
    }
}
