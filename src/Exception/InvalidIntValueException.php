<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;

class InvalidIntValueException extends InvalidArgumentException
{
    public function __construct(string $key)
    {
        parent::__construct(sprintf('Invalid integer value for key: %s.', $key));
    }
}
