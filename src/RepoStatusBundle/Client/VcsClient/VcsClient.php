<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client\VcsClient;

use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Model\Commit;
use App\RepoStatusBundle\Model\PullRequest;

interface VcsClient
{
    /**
     * @param GitHubQueryParams|null $queryParams
     * @return PullRequest[]
     */
    public function getPullRequests(?GitHubQueryParams $queryParams = null): array;

    /**
     * @param GitHubQueryParams|null $queryParams
     * @return Commit[]
     */
    public function getCommits(?GitHubQueryParams $queryParams = null): array;

    /**
     * @param GitHubQueryParams|null $queryParams
     * @return int
     */
    public function getPullRequestsCount(?GitHubQueryParams $queryParams = null): int;

    /**
     * @param GitHubQueryParams|null $queryParams
     * @return int
     */
    public function getCommitsCount(?GitHubQueryParams $queryParams = null): int;
}
