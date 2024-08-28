<?php

declare(strict_types=1);

namespace App\Config;

class RepositoryConfig
{
    public string $type;
    public string $token;
    public string $repo;

    public function __construct(string $type, string $token, string $repo)
    {
        $this->type = $type;
        $this->token = $token;
        $this->repo = $repo;
    }
}
