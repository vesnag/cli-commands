<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Config;

class GitLabConfig
{
    public function __construct(
        public readonly string $owner,
        public readonly string $repo
    ) {
    }
}
