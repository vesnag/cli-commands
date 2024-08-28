<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Tests\Command;

use AG\RepoStatusBundle\Client\GitHubClient;
use AG\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
use AG\RepoStatusBundle\Service\RepositoryStatusChecker;
use AG\RepoStatusBundle\Model\PullRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CheckRepositoryStatusCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $gitHubClient = $this->createMock(GitHubClient::class);
        $gitHubClient->method('getPullRequests')->willReturn([
            new PullRequest(1, 'PR Title 1', 'author1', 'https://github.com/owner/repo/pull/1', 'open'),
            new PullRequest(2, 'PR Title 2', 'author2', 'https://github.com/owner/repo/pull/2', 'closed'),
            new PullRequest(3, 'PR Title 3', 'author3', 'https://github.com/owner/repo/pull/3', 'open'),
        ]);

        $statusChecker = new RepositoryStatusChecker($gitHubClient);

        $command = new CheckRepositoryStatusCommand($statusChecker);

        $application = new Application();
        $application->add($command);

        $command = $application->find('app:check-repository-status');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Open pull requests: 2', $output);
    }
}
