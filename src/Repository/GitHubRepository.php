<?php

declare(strict_types=1);

namespace App\Repository;

class GitHubRepository implements RepositoryInterface
{
    public function __construct(
        private array $config
    ) {
    }

    public function checkStatus(): void
    {
        // Use GitHub API to check status
        // Return status
        return 'Repository status';
    }
}
