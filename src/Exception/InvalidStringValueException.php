<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;

class InvalidStringValueException extends InvalidArgumentException
{
    public function __construct(string $key)
    {
        parent::__construct(sprintf('Invalid string value for key: %s.', $key));
    }
}
