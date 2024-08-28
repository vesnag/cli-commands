<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Service;

use AG\RepoStatusBundle\Client\GitHubClient;

class RepositoryStatusChecker
{
    private GitHubClient $gitHubClient;

    public function __construct(GitHubClient $gitHubClient)
    {
        $this->gitHubClient = $gitHubClient;
    }

    public function getOpenPullRequestsCount(): int
    {
        $pullRequests = $this->gitHubClient->getPullRequests();
        $openPullRequests = array_filter($pullRequests, fn($pr) => $pr->state === 'open');

        return count($openPullRequests);
    }
}
