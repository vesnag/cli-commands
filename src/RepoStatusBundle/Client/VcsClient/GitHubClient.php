<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Client\VcsClient;

use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Model\Commit;
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
     * @param GitHubQueryParams|null $queryParams
     * @return PullRequest[]
     */
    public function getPullRequests(?GitHubQueryParams $queryParams = null): array
    {
        $apiUrl = $this->urlBuilder->constructApiUrl('pulls');
        $pullRequestsData = $this->fetchData($apiUrl, $queryParams);

        return array_map([$this, 'mapToPullRequest'], $pullRequestsData);
    }

    /**
     * @param GitHubQueryParams|null $queryParams
     * @return Commit[]
     */
    public function getCommits(?GitHubQueryParams $queryParams = null): array
    {
        $apiUrl = $this->urlBuilder->constructApiUrl('commits');
        $commitsData = $this->fetchData($apiUrl, $queryParams);

        return array_map([$this, 'mapToCommit'], $commitsData);
    }

    /**
     * @param GitHubQueryParams|null $queryParams
     * @return int
     */
    public function getPullRequestsCount(?GitHubQueryParams $queryParams = null): int
    {
        return count($this->getPullRequests($queryParams));
    }

    /**
     * @param GitHubQueryParams|null $queryParams
     * @return int
     */
    public function getCommitsCount(?GitHubQueryParams $queryParams = null): int
    {
        return count($this->getCommits($queryParams));
    }

    /**
     * @param string $apiUrl
     * @param GitHubQueryParams|null $queryParams
     * @return array<int, array<string, mixed>>
     */
    private function fetchData(string $apiUrl, ?GitHubQueryParams $queryParams = null): array
    {
        $queryParamsArray = $queryParams?->getParams() ?? [];

        $response = $this->httpClient->request('GET', $apiUrl, [
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
            'query' => $queryParamsArray,
        ]);

        return $response->toArray();
    }

    /**
     * Maps raw pull request data to a PullRequest object.
     *
     * @param array<string, mixed> $prData
     * @return PullRequest
     */
    private function mapToPullRequest(array $prData): PullRequest
    {
        $user = is_array($prData['user'] ?? null) ? $prData['user'] : [];
        $userLogin = ArrayUtils::get($user, 'login', '');

        return new PullRequest(
            id: ConvertTo::int($prData['id'], 'id'),
            title: ConvertTo::string($prData['title'], 'title'),
            author: ConvertTo::string($userLogin, 'user.login'),
            url: ConvertTo::string($prData['html_url'], 'html_url'),
            state: ConvertTo::string($prData['state'], 'state')
        );
    }

    /**
     * Maps raw commit data to a Commit object.
     *
     * @param array<string, mixed> $commitData
     * @return Commit
     */
    private function mapToCommit(array $commitData): Commit
    {
        $commit = is_array($commitData['commit'] ?? null) ? $commitData['commit'] : [];
        $author = is_array($commit['author'] ?? null) ? $commit['author'] : [];
        $authorName = ArrayUtils::get($author, 'name', '');
        $authorDate = ArrayUtils::get($author, 'date', '');

        return new Commit(
            id: ConvertTo::int($commitData['sha'] ?? '', 'sha'),
            message: ConvertTo::string($commit['message'] ?? '', 'commit.message'),
            author: ConvertTo::string($authorName, 'commit.author.name'),
            url: ConvertTo::string($commitData['html_url'] ?? '', 'html_url'),
            date: new \DateTime(is_string($authorDate) ? $authorDate : 'now')
        );
    }
}
