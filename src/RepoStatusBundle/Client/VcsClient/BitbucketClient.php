<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client\VcsClient;

class BitbucketClient implements VcsClient
{
    public function getPullRequests(): array
    {
        return [];
    }
}
