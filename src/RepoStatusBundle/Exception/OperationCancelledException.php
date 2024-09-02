<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Exception;

use Symfony\Component\Console\Exception\RuntimeException;

class OperationCancelledException extends RuntimeException
{
    public function __construct(string $message = 'The operation was cancelled by the user.', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
