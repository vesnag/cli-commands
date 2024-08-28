<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Config;

class GitHubConfig
{
    public function __construct(
        public readonly string $owner,
        public readonly string $repo
    ) {
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getRepo(): string
    {
        return $this->repo;
    }
}
