<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

use AG\RepoStatusBundle\Config\GitHubConfig;
use AG\RepoStatusBundle\Model\PullRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitLabClient implements VcsClient
{
    private GitHubConfig $config;
    private HttpClientInterface $httpClient;

    public function __construct(GitHubConfig $config, HttpClientInterface $httpClient)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
    }

    public function getPullRequests(): array
    {
        // Dummy implementation
        return [];
    }
}
