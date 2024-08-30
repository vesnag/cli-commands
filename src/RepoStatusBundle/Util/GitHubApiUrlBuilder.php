<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Util;

use App\RepoStatusBundle\Config\GitHubConfig;

class GitHubApiUrlBuilder
{
    public function __construct(
        private string $baseUrl,
        private GitHubConfig $config
    ) {
    }

    public function constructApiUrl(string $endpoint): string
    {
        return sprintf(
            '%s/repos/%s/%s/%s',
            $this->baseUrl,
            $this->config->getOwner(),
            $this->config->getRepo(),
            $endpoint
        );
    }
}
