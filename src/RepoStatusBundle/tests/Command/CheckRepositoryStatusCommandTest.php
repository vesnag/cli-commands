<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Command;

use App\RepoStatusBundle\Client\GitHubClient;
use App\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
use App\RepoStatusBundle\Config\GitHubConfig;
use App\RepoStatusBundle\Model\PullRequest;
use App\RepoStatusBundle\Service\QuestionAsker;
use App\RepoStatusBundle\Service\QuestionAnswerHandler;
use App\RepoStatusBundle\Service\RepositoryStatusChecker;
use App\RepoStatusBundle\Service\ResponseProcessor;
use App\RepoStatusBundle\Service\MessageGenerator;
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

        $repositoryStatusChecker = new RepositoryStatusChecker($gitHubClient);

        $githubConfig = $this->createMock(GitHubConfig::class);
        $questionAsker = $this->createMock(QuestionAsker::class);
        $messageGenerator = new MessageGenerator();
        $responseProcessor = new ResponseProcessor($repositoryStatusChecker, $messageGenerator);
        $questionAnswerHandler = new QuestionAnswerHandler($responseProcessor);

        $questionAsker->method('askQuestions')->willReturn([
            CheckRepositoryStatusCommand::TIME_PERIOD => 'entire history',
            CheckRepositoryStatusCommand::GET_COUNT_PRS => true,
            CheckRepositoryStatusCommand::GET_COUNT_COMMITS => false,
            CheckRepositoryStatusCommand::GENERATE_SLACK_REPORT => false,
            CheckRepositoryStatusCommand::PUBLISH_TO_SLACK => false,
        ]);

        $command = new CheckRepositoryStatusCommand($githubConfig, $questionAsker, $questionAnswerHandler);

        $application = new Application();
        $application->add($command);

        $command = $application->find('app:check-repository-status');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Pull requests for the selected period: 3', $output);
    }
}
