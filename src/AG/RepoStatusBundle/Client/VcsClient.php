<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

use AG\RepoStatusBundle\Model\PullRequest;

interface VcsClient
{
    /**
     * @return PullRequest[]
     */
    public function getPullRequests(): array;
}
