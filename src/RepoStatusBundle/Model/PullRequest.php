<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Model;

class PullRequest
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $author,
        public readonly string $url,
        public readonly string $state
    ) {
    }
}
