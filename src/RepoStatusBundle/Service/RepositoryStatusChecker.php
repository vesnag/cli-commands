<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Client\GitHubClient;
use App\RepoStatusBundle\Model\PullRequest;

class RepositoryStatusChecker
{
    public function __construct(
        private GitHubClient $gitHubClient
    ) {
    }

    public function getOpenPullRequestsCount(): int
    {
        $pullRequests = $this->gitHubClient->getPullRequests();
        $openPullRequests = array_filter($pullRequests, fn($pr) => $pr->state === 'open');
        return count($openPullRequests);
    }

    /**
     * @return PullRequest[]
     */
    public function getPullRequestsForDateRange(?string $startDate = null, ?string $endDate = null): array
    {
        return $this->gitHubClient->getPullRequests($startDate, $endDate);
    }

    public function getCommitCount(?string $startDate = null, ?string $endDate = null): int
    {
        return $this->gitHubClient->getCommitCount($startDate, $endDate);
    }
}
