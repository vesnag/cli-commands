<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Command;

use App\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
use App\RepoStatusBundle\Collector\QuestionCollector;
use App\RepoStatusBundle\Service\ReportGeneratorInterface;
use App\RepoStatusBundle\Service\SlackPublisher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Question\Question;
use App\RepoStatusBundle\Question\QuestionInterface;

class CheckRepositoryStatusCommandTest extends TestCase
{
    public function testExecuteWithValidResponses(): void
    {
        $questionCollector = $this->createMock(QuestionCollector::class);
        $question = $this->createMock(QuestionInterface::class);

        $questionCollector->method('getQuestions')->willReturn([$question]);

        $question->method('createQuestion')
            ->willReturn($this->createMock(Question::class));
        $question->method('handleResponse')
            ->willReturnCallback(function ($response, $responses, $input, $output) use ($question) {
                $responses->addResponse('get_count_prs', true, $question);
            });

        $reportGenerator = $this->createMock(ReportGeneratorInterface::class);
        $reportGenerator->method('generateReportMessage')
            ->willReturn('Report: Number of PRs: 3');

        $slackPublisher = $this->createMock(SlackPublisher::class);
        $slackPublisher->method('publishToSlack')
            ->willReturnCallback(function ($reportMessage, $output) {
                $output->writeln('Message successfully posted to Slack.');
            });

        $command = new CheckRepositoryStatusCommand($questionCollector, $reportGenerator, $slackPublisher);

        $application = new Application();
        $application->add($command);

        $questionHelper = $this->createMock(QuestionHelper::class);
        $questionHelper->method('ask')
            ->willReturn(true);

        $helperSet = $command->getHelperSet() ?? new HelperSet();
        $helperSet->set($questionHelper, 'question');
        $command->setHelperSet($helperSet);

        $command = $application->find('app:check-repository-status');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Report: Number of PRs: 3', $output);
        $this->assertStringContainsString('Message successfully posted to Slack.', $output);
    }

    public function testExecuteWithInvalidResponses(): void
    {
        $questionCollector = $this->createMock(QuestionCollector::class);
        $question = $this->createMock(QuestionInterface::class);

        $questionCollector->method('getQuestions')->willReturn([$question]);

        $question->method('createQuestion')
            ->willReturn($this->createMock(Question::class));
        $question->method('handleResponse')
            ->willReturnCallback(function ($response, $responses, $input, $output) use ($question) {
                $responses->addResponse('get_count_prs', true, $question);
            });

        $reportGenerator = $this->createMock(ReportGeneratorInterface::class);
        $reportGenerator->method('generateReportMessage')
            ->willReturn('Report: Number of PRs: 3');

        $slackPublisher = $this->createMock(SlackPublisher::class);
        $slackPublisher->method('publishToSlack')
            ->willReturnCallback(function ($reportMessage, $output) {
                $output->writeln('Message successfully posted to Slack.');
            });

        $command = new CheckRepositoryStatusCommand($questionCollector, $reportGenerator, $slackPublisher);

        $application = new Application();
        $application->add($command);

        $questionHelper = $this->createMock(QuestionHelper::class);
        $questionHelper->method('ask')
            ->willReturn(null);

        $helperSet = $command->getHelperSet() ?? new HelperSet();
        $helperSet->set($questionHelper, 'question');
        $command->setHelperSet($helperSet);

        $command = $application->find('app:check-repository-status');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Invalid response type received.', $output);
    }
}
