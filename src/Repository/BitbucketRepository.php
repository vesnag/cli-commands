<?php

declare(strict_types=1);

namespace App\Repository;

class BitbucketRepository implements RepositoryInterface
{
    public function __construct(
        private array $config
    ) {
    }

    public function checkStatus()
    {
        // Use Bitbucket API to check status
        // Return status
    }
}
