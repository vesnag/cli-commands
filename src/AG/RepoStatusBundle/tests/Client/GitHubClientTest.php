<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Client;

use AG\RepoStatusBundle\Config\GitHubConfig;
use AG\RepoStatusBundle\Model\PullRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GitHubClientTest extends TestCase
{
    public function testGetPullRequests(): void
    {
        $config = $this->createMock(GitHubConfig::class);
        $config->method('getOwner')->willReturn('owner');
        $config->method('getRepo')->willReturn('repo');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $responseData = [
            [
                'id' => 1,
                'title' => 'PR Title',
                'user' => ['login' => 'author'],
                'html_url' => 'https://github.com/owner/repo/pull/1',
                'state' => 'open',
            ],
        ];

        $response->method('toArray')->willReturn($responseData);
        $httpClient->method('request')->willReturn($response);

        $client = new GitHubClient($config, $httpClient);

        $pullRequests = $client->getPullRequests();

        $this->assertCount(1, $pullRequests);
        $this->assertInstanceOf(PullRequest::class, $pullRequests[0]);
        $this->assertEquals(1, $pullRequests[0]->id);
        $this->assertEquals('PR Title', $pullRequests[0]->title);
        $this->assertEquals('author', $pullRequests[0]->author);
        $this->assertEquals('https://github.com/owner/repo/pull/1', $pullRequests[0]->url);
    }
}
