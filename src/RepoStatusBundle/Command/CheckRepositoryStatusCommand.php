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
     * @param QuestionCollector<bool|string> $questionCollector
     * @param ReportGeneratorInterface<bool|string> $reportGenerator
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

        /** @var ResponseCollection<bool|string> $responseCollection */
        $responseCollection = new ResponseCollection();

        foreach ($this->questionCollector->getQuestions() as $question) {
            $response = $questionHelper->ask($input, $output, $question->createQuestion());

            if ($this->isValidResponseType($response)) {
                 /** @var bool|string $response */
                $this->handleQuestionResponse($question, $response, $responseCollection, $input, $output);
            } else {
                $output->writeln('<error>Invalid response type received.</error>');
                return Command::FAILURE;
            }
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
     * @param QuestionInterface<bool|string> $question
     * @param bool|string $response
     * @param ResponseCollection<bool|string> $responses
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    private function handleQuestionResponse(
        QuestionInterface $question,
        bool|string $response,
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

    /**
     * Validate if the response is of type bool or string.
     *
     * @param mixed $response
     * @return bool
     */
    private function isValidResponseType(mixed $response): bool
    {
        return is_bool($response) || is_string($response);
    }
}
