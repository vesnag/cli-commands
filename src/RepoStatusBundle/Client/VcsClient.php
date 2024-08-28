<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client;

use App\RepoStatusBundle\Model\PullRequest;

interface VcsClient
{
    /**
     * @return PullRequest[]
     */
    public function getPullRequests(): array;
}
