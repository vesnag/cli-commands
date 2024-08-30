<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client;

use App\RepoStatusBundle\Model\PullRequest;
use App\RepoStatusBundle\Util\GitHubApiUrlBuilder;
use App\Utils\ArrayUtils;
use App\Utils\ConvertTo;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubClient implements VcsClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private GitHubApiUrlBuilder $urlBuilder
    ) {
    }

    /**
     * @return PullRequest[]
     */
    public function getPullRequests(?string $startDate = null, ?string $endDate = null): array
    {
        $apiUrl = $this->urlBuilder->constructApiUrl('pulls');
        $pullRequestsData = $this->fetchData($apiUrl, $startDate, $endDate);


        return array_map(function ($prData) {
            $user = is_array($prData['user'] ?? null) ? $prData['user'] : [];
            $userLogin = ArrayUtils::get($user, 'login', '');

            return new PullRequest(
                id: ConvertTo::int($prData['id'], 'id'),
                title: ConvertTo::string($prData['title'], 'title'),
                author: ConvertTo::string($userLogin, 'user.login'),
                url: ConvertTo::string($prData['html_url'], 'html_url'),
                state: ConvertTo::string($prData['state'], 'state')
            );
        }, $pullRequestsData);
    }

    public function getCommitCount(?string $startDate = null, ?string $endDate = null): int
    {
        $apiUrl = $this->urlBuilder->constructApiUrl('commits');
        $commitsData = $this->fetchData($apiUrl, $startDate, $endDate);

        return count($commitsData);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchData(string $apiUrl, ?string $startDate, ?string $endDate): array
    {
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


        return $response->toArray();
    }
}
