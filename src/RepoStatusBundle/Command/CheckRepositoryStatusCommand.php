<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Command;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Collector\QuestionCollector;
use App\RepoStatusBundle\Exception\OperationCancelledException;
use App\RepoStatusBundle\Question\QuestionInterface;
use App\RepoStatusBundle\Service\ReportGeneratorInterface;
use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:check-repository-status')]
final class CheckRepositoryStatusCommand extends Command
{
    protected static string $defaultName = 'app:check-repository-status';

    /**
     * @param QuestionCollector<mixed> $questionCollector
     * @param ReportGeneratorInterface<string> $reportGenerator
     */
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
            ->setDescription('Checks the status of the repository and sends a notification.')
            ->setHelp('This command checks the status of the repository and sends a notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        /** @var ResponseCollection<mixed> $responseCollection */
        $responseCollection = new ResponseCollection();

        foreach ($this->questionCollector->getQuestions() as $question) {
            $response = $questionHelper->ask($input, $output, $question->createQuestion());
            $this->handleQuestionResponse($question, $response, $responseCollection, $input, $output);
        }

        $reportMessage = $this->reportGenerator->generateReportMessage($responseCollection);

        if ($responseCollection->getResponse('publish_to_slack')) {
            $this->sendSlackMessage($reportMessage, $output);
        }

        $output->writeln($reportMessage);

        return Command::SUCCESS;
    }

    /**
     * Handles the response for a given question.
     *
     * @param QuestionInterface<mixed> $question
     * @param mixed $response
     * @param ResponseCollection<mixed> $responses
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    private function handleQuestionResponse(
        QuestionInterface $question,
        $response,
        ResponseCollection $responses,
        InputInterface $input,
        OutputInterface $output
    ): int {
        try {
            $question->handleResponse($response, $responses, $input, $output);
        } catch (OperationCancelledException $e) {
            $output->writeln($e->getMessage());
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>An error occurred: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function sendSlackMessage(string $reportMessage, OutputInterface $output): void
    {
        $sendMessageSuccess = $this->messageSender->sendMessage($reportMessage);

        $message = $sendMessageSuccess ? 'Message successfully posted to Slack.' : 'Failed to post message to Slack.';
        $output->writeln($message);
    }
}
