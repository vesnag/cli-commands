<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Command;

use App\RepoStatusBundle\Client\GitHubClient;
use App\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
use App\RepoStatusBundle\Config\GitHubConfig;
use App\RepoStatusBundle\Model\PullRequest;
use App\RepoStatusBundle\Service\QuestionCollector;
use App\RepoStatusBundle\Service\QuestionAsker;
use App\RepoStatusBundle\Service\QuestionAnswerHandler;
use App\RepoStatusBundle\Service\RepositoryStatusChecker;
use App\RepoStatusBundle\Service\ResponseProcessor;
use App\RepoStatusBundle\Service\MessageGenerator;
use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CheckRepositoryStatusCommandTest extends TestCase
{
    public function testExecute(): void
    {
        // Mock GitHubClient and its method
        $gitHubClient = $this->createMock(GitHubClient::class);
        $gitHubClient->method('getPullRequests')->willReturn([
            new PullRequest(1, 'PR Title 1', 'author1', 'https://github.com/owner/repo/pull/1', 'open'),
            new PullRequest(2, 'PR Title 2', 'author2', 'https://github.com/owner/repo/pull/2', 'closed'),
            new PullRequest(3, 'PR Title 3', 'author3', 'https://github.com/owner/repo/pull/3', 'open'),
        ]);

        $repositoryStatusChecker = new RepositoryStatusChecker($gitHubClient);

        $questionAsker = $this->createMock(QuestionAsker::class);

        $messageGenerator = new MessageGenerator();
        $messageSender = $this->createMock(MessageSender::class);

        $responseProcessor = new ResponseProcessor(
            $repositoryStatusChecker,
            $messageGenerator,
            $messageSender
        );
        $questionAnswerHandler = new QuestionAnswerHandler($responseProcessor);

        $questionAsker->method('askQuestions')->willReturn([
            'time' => 'entire history',
            'get_count_prs' => true,
            'get_count_commits' => false,
            'generate_slack_report' => false,
            'publish_to_slack' => false,
        ]);

        $command = new CheckRepositoryStatusCommand($questionAsker, $questionAnswerHandler);

        $application = new Application();
        $application->add($command);

        $command = $application->find('app:check-repository-status');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Pull requests for the selected period: 3', $output);
    }
}
