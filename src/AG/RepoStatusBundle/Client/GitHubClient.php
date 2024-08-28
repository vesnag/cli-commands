<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

use AG\RepoStatusBundle\Config\GitHubConfig;
use AG\RepoStatusBundle\Model\PullRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubClient implements VcsClient
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
        $owner = $this->config->getOwner();
        $repo = $this->config->getRepo();
        $apiUrl = "https://api.github.com/repos/{$owner}/{$repo}/pulls";

        $response = $this->httpClient->request('GET', $apiUrl, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);

        $pullRequestsData = $response->toArray();

        $pullRequests = [];
        foreach ($pullRequestsData as $prData) {
            $pullRequests[] = new PullRequest(
                id: $prData['id'],
                title: $prData['title'],
                author: $prData['user']['login'],
                url: $prData['html_url'],
                state: $prData['state']
            );
        }

        return $pullRequests;
    }
}
