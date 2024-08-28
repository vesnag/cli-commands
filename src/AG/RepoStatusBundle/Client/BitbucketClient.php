<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

class BitbucketClient implements VcsClient
{
    public function getPullRequests(): array
    {
        return [];
    }
}
