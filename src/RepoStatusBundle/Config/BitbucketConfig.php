<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Config;

class BitbucketConfig
{
    public function __construct(
        public readonly string $owner,
        public readonly string $repo
    ) {
    }
}
