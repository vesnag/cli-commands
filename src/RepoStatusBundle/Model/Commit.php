<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Model;

class Commit
{
    public function __construct(
        public readonly int $id,
        public readonly string $message,
        public readonly string $author,
        public readonly string $url,
        public readonly \DateTimeInterface $date
    ) {
    }
}
