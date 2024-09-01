<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Command;

use App\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
use App\RepoStatusBundle\Collector\QuestionCollector;
use App\RepoStatusBundle\Service\ReportGeneratorInterface;
use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;

class CheckRepositoryStatusCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $questionCollector = $this->createMock(QuestionCollector::class);
        $question = $this->createMock(\App\RepoStatusBundle\Question\QuestionInterface::class);

        $questionCollector->method('getQuestions')->willReturn([$question]);

        $question->method('createQuestion')
            ->willReturn($this->createMock(\Symfony\Component\Console\Question\Question::class));
        $question->method('handleResponse')
            ->willReturnCallback(function ($response, $responses, $input, $output) use ($question) {
                $responses->addResponse('get_count_prs', true, $question);
            });

        $reportGenerator = $this->createMock(ReportGeneratorInterface::class);
        $reportGenerator->method('generateReportMessage')
            ->willReturn('Report: Number of PRs: 3');

        $messageSender = $this->createMock(MessageSender::class);
        $messageSender->method('sendMessage')
            ->willReturn(true);

        $command = new CheckRepositoryStatusCommand($questionCollector, $reportGenerator, $messageSender);

        $application = new Application();
        $application->add($command);

        $questionHelper = $this->createMock(QuestionHelper::class);
        $questionHelper->method('ask')
            ->willReturn(true);

        $helperSet = $command->getHelperSet();
        if ($helperSet === null) {
            $helperSet = new HelperSet();
            $command->setHelperSet($helperSet);
        }
        $helperSet->set($questionHelper, 'question');

        $command = $application->find('app:check-repository-status');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Report: Number of PRs: 3', $output);
    }
}
