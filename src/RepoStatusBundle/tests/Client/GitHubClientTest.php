<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Client;

use App\RepoStatusBundle\Client\GitHubClient;
use App\RepoStatusBundle\Util\GitHubApiUrlBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GitHubClientTest extends TestCase
{
    public function testGetPullRequests(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $urlBuilder = $this->createMock(GitHubApiUrlBuilder::class);

        $urlBuilder->method('constructApiUrl')
            ->willReturn('https://api.github.com/repos/user/repo/pulls');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')
            ->willReturn([
                [
                    'id' => 1,
                    'title' => 'Test PR',
                    'user' => ['login' => 'testuser'],
                    'html_url' => 'https://github.com/user/repo/pull/1',
                    'state' => 'open',
                ],
            ]);

        $httpClient->method('request')
            ->willReturn($response);

        $client = new GitHubClient($httpClient, $urlBuilder);

        $pullRequests = $client->getPullRequests();

        $this->assertCount(1, $pullRequests);
        $this->assertSame(1, $pullRequests[0]->getId());
        $this->assertSame('Test PR', $pullRequests[0]->getTitle());
        $this->assertSame('testuser', $pullRequests[0]->getAuthor());
        $this->assertSame('https://github.com/user/repo/pull/1', $pullRequests[0]->getUrl());
        $this->assertSame('open', $pullRequests[0]->getState());
    }

    public function testGetCommitCount(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $urlBuilder = $this->createMock(GitHubApiUrlBuilder::class);

        $urlBuilder->method('constructApiUrl')
            ->willReturn('https://api.github.com/repos/user/repo/commits');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('toArray')
            ->willReturn([
                ['sha' => 'abc123'],
                ['sha' => 'def456'],
            ]);

        $httpClient->method('request')
            ->willReturn($response);

        $client = new GitHubClient($httpClient, $urlBuilder);

        $commitCount = $client->getCommitCount();

        $this->assertSame(2, $commitCount);
    }
}
