<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Command;

use App\RepoStatusBundle\Client\GitHubClient;
use App\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Model\PullRequest;
use App\RepoStatusBundle\Service\QuestionCollector;
use App\RepoStatusBundle\Service\ReportGeneratorInterface;
use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;

class CheckRepositoryStatusCommandTest extends TestCase
{
    public function testExecute(): void
    {
        // Mock GitHubClient and its method
        $gitHubClient = $this->createMock(GitHubClient::class);

        // Mock QuestionCollector to provide a list of questions
        $questionCollector = $this->createMock(QuestionCollector::class);
        $question = $this->createMock(\App\RepoStatusBundle\Question\QuestionInterface::class);

        $questionCollector->method('getQuestions')->willReturn([$question]);

        // Set up Question behavior
        $question->method('createQuestion')
            ->willReturn($this->createMock(\Symfony\Component\Console\Question\Question::class));
        $question->method('handleResponse')
            ->willReturnCallback(function ($response, $responses, $input, $output) {
                $responses->addResponse('get_count_prs', true);
            });

        // Mock ReportGeneratorInterface to generate a report message
        $reportGenerator = $this->createMock(ReportGeneratorInterface::class);
        $reportGenerator->method('generateReportMessage')
            ->willReturn('Report: Number of PRs: 3');

        // Mock MessageSender
        $messageSender = $this->createMock(MessageSender::class);
        $messageSender->method('sendMessage')
            ->willReturn(['success' => true]);

        // Instantiate the command with the necessary services
        $command = new CheckRepositoryStatusCommand($questionCollector, $reportGenerator, $messageSender);

        // Set up the Symfony Console application and add the command
        $application = new Application();
        $application->add($command);

        // Mock the QuestionHelper
        $questionHelper = $this->createMock(QuestionHelper::class);
        $questionHelper->method('ask')
            ->willReturn(true);  // Return whatever response your questions expect

        // Add the QuestionHelper to the command
        $command->getHelperSet()->set($questionHelper, 'question');

        // Find the command and prepare the CommandTester
        $command = $application->find('app:check-repository-status');
        $commandTester = new CommandTester($command);

        // Execute the command
        $commandTester->execute([]);

        // Get the output and make assertions
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Report: Number of PRs: 3', $output);
        $this->assertStringContainsString('Message successfully posted to Slack.', $output);
    }
}
