<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

use AG\RepoStatusBundle\Config\GitHubConfig;
use AG\RepoStatusBundle\Model\PullRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitLabClient implements VcsClient
{
    public function getPullRequests(): array
    {
        return [];
    }
}
