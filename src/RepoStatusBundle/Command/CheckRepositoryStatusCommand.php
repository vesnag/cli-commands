<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Command;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Collector\QuestionCollector;
use App\RepoStatusBundle\Exception\OperationCancelledException;
use App\RepoStatusBundle\Question\PublishToSlackQuestion;
use App\RepoStatusBundle\Question\QuestionInterface;
use App\RepoStatusBundle\Service\ReportGeneratorInterface;
use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use App\RepoStatusBundle\Service\SlackPublisher;
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
        private readonly SlackPublisher $slackPublisher,
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

            if (!$this->isValidResponseType($response)) {
                $output->writeln('<error>Invalid response type received.</error>');
                return Command::FAILURE;
            }

             /** @var bool|string $response */
            $this->handleQuestionResponse($question, $response, $responseCollection, $input, $output);
        }

        $reportMessage = $this->reportGenerator->generateReportMessage($responseCollection);

        /** @var PublishToSlackQuestion $publishToSlackQuestion */
        $publishToSlackQuestion = $this->questionCollector->getQuestionByKey('publish_to_slack');
        if ($publishToSlackQuestion->shouldPublishToSlack()) {
            $this->slackPublisher->publishToSlack($reportMessage, $output);
        }

        $output->writeln($reportMessage);

        return Command::SUCCESS;
    }

    /**
     * @param QuestionInterface<bool|string> $question
     * @param bool|string $response
     * @param ResponseCollection<bool|string> $responses
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function handleQuestionResponse(
        QuestionInterface $question,
        bool|string $response,
        ResponseCollection $responses,
        InputInterface $input,
        OutputInterface $output
    ): void {
        try {
            $question->handleResponse($response, $responses, $input, $output);
        } catch (\Throwable $e) {
            $this->handleException($e, $output);
        }
    }

    private function handleException(\Throwable $e, OutputInterface $output): void
    {
        if ($e instanceof OperationCancelledException) {
            $output->writeln($e->getMessage());
        } else {
            $output->writeln('<error>An error occurred: ' . $e->getMessage() . '</error>');
        }
    }

    private function isValidResponseType(mixed $response): bool
    {
        return is_bool($response) || is_string($response);
    }
}
