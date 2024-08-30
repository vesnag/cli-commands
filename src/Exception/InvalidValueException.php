<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;

class InvalidValueException extends InvalidArgumentException
{
    public function __construct(string $key, string $expectedType)
    {
        parent::__construct(sprintf('Invalid value for key: %s. Expected type: %s.', $key, $expectedType));
    }
}
