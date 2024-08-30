<?php

declare(strict_types=1);

namespace App\Utils;

class ArrayUtils
{
    /**
     * Get a value from an array or return a default value if it does not exist.
     *
     * @param array<string, mixed> $array The array to search in.
     * @param string $key The key to look for.
     * @param mixed $default The default value to return if the key does not exist.
     * @return mixed The value from the array or the default value.
     */
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        return $array[$key] ?? $default;
    }
}
