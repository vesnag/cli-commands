<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Client\GitHubClient;
use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Model\PullRequest;
use App\RepoStatusBundle\Model\Commit;

class RepositoryStatusChecker
{
    public function __construct(
        private readonly GitHubClient $gitHubClient,
        private readonly GitHubQueryParams $gitHubQueryParams
    ) {
    }

    /**
     * Get the count of open pull requests.
     *
     * @return int
     */
    public function getOpenPullRequestsCount(): int
    {
        return $this->gitHubClient->getPullRequestsCount($this->gitHubQueryParams);
    }

    /**
     * Get all pull requests within the specified date range.
     *
     * @return PullRequest[]
     */
    public function getPullRequestsForDateRange(): array
    {
        return $this->gitHubClient->getPullRequests($this->gitHubQueryParams);
    }

    /**
     * Get the count of commits within the specified date range.
     *
     * @return int
     */
    public function getCommitsCount(): int
    {
        return $this->gitHubClient->getCommitsCount($this->gitHubQueryParams);
    }

    /**
     * Get all commits within the specified date range.
     *
     * @return Commit[]
     */
    public function getCommitsForDateRange(): array
    {
        return $this->gitHubClient->getCommits($this->gitHubQueryParams);
    }
}
