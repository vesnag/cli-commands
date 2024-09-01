<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Command;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Collector\QuestionCollector;
use App\RepoStatusBundle\Service\ReportGeneratorInterface;
use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckRepositoryStatusCommand extends Command
{
    protected static string $defaultName = 'app:check-repository-status';

    public function __construct(
        private readonly QuestionCollector $questionCollector,
        private readonly ReportGeneratorInterface $reportGenerator,
        private readonly MessageSender $messageSender
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Checks the status of the repository and sends a notification.')
            ->setHelp('This command checks the status of the repository and sends a notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $questions = iterator_to_array($this->questionCollector->getQuestions());
        $responses = new ResponseCollection();

        foreach ($questions as $question) {
            $response = $questionHelper->ask($input, $output, $question->createQuestion());
            $question->handleResponse($response, $responses, $input, $output);
        }

        $reportMessage = $this->reportGenerator->generateReportMessage($responses);

        if ($responses->getResponse('publish_to_slack')) {
            $this->sendSlackMessage($reportMessage, $output);
        }

        $output->writeln($reportMessage);

        return Command::SUCCESS;
    }

    private function sendSlackMessage(string $reportMessage, OutputInterface $output): void
    {
        $sendMessageSuccess = $this->messageSender->sendMessage($reportMessage);

        $message = $sendMessageSuccess ? 'Message successfully posted to Slack.' : 'Failed to post message to Slack.';
        $output->writeln($message);
    }
}
