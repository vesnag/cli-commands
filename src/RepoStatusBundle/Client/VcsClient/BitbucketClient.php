<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client\VcsClient;

use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Model\Commit;
use App\RepoStatusBundle\Model\PullRequest;

class BitbucketClient implements VcsClient
{
    /**
     * @inheritDoc
     */
    public function getPullRequests(?GitHubQueryParams $queryParams = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getCommits(?GitHubQueryParams $queryParams = null): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getPullRequestsCount(?GitHubQueryParams $queryParams = null): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getCommitsCount(?GitHubQueryParams $queryParams = null): int
    {
        return 0;
    }
}
