<?php

declare(strict_types=1);

namespace App\Repository;

class BitbucketRepository implements RepositoryInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function checkStatus()
    {
        // Use Bitbucket API to check status
        // Return status
    }
}
