<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client\VcsClient;

class GitLabClient implements VcsClient
{
    public function getPullRequests(): array
    {
        return [];
    }
}
