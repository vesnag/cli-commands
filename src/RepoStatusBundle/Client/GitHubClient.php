<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client;

use App\RepoStatusBundle\Config\GitHubConfig;
use App\RepoStatusBundle\Model\PullRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubClient implements VcsClient
{
    public function __construct(
        private GitHubConfig $config,
        private HttpClientInterface $httpClient
    ) {
    }

    public function getPullRequests(?string $startDate = null, ?string $endDate = null): array
    {
        $apiUrl = sprintf(
            'https://api.github.com/repos/%s/%s/pulls',
            $this->config->getOwner(),
            $this->config->getRepo()
        );

        $queryParams = [];
        if ($startDate) {
            $queryParams['since'] = $startDate;
        }
        if ($endDate) {
            $queryParams['until'] = $endDate;
        }

        $response = $this->httpClient->request('GET', $apiUrl, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
            'query' => $queryParams,
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

    public function getCommitCount(?string $startDate = null, ?string $endDate = null): int
    {
        $apiUrl = sprintf(
            'https://api.github.com/repos/%s/%s/commits',
            $this->config->getOwner(),
            $this->config->getRepo()
        );

        $queryParams = [];
        if ($startDate) {
            $queryParams['since'] = $startDate;
        }
        if ($endDate) {
            $queryParams['until'] = $endDate;
        }

        $response = $this->httpClient->request('GET', $apiUrl, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
            'query' => $queryParams,
        ]);

        $commitsData = $response->toArray();

        return count($commitsData);
    }
}
