<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

use AG\RepoStatusBundle\Config\GitHubConfig;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BitbucketClient implements VcsClient
{
    public function getPullRequests(): array
    {
        // Dummy implementation
        return [];
    }
}
