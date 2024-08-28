<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

use AG\RepoStatusBundle\Config\GitHubConfig;
use AG\RepoStatusBundle\Model\PullRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubClient implements VcsClient
{
    public function __construct(
        private GitHubConfig $config,
        private HttpClientInterface $httpClient
    ) {
    }

    public function getPullRequests(): array
    {
        $apiUrl = sprintf(
            'https://api.github.com/repos/%s/%s/pulls',
            $this->config->getOwner(),
            $this->config->getRepo()
        );

        $response = $this->httpClient->request('GET', $apiUrl, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);

        $pullRequestsData = $response->toArray();

        return array_map(fn($prData) => new PullRequest(
            id: $prData['id'],
            title: $prData['title'],
            author: $prData['user']['login'],
            url: $prData['html_url'],
            state: $prData['state']
        ), $pullRequestsData);
    }
}
