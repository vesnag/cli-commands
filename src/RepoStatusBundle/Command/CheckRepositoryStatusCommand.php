<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Command;

use App\RepoStatusBundle\Service\QuestionAsker;
use App\RepoStatusBundle\Service\QuestionCollector;
use App\RepoStatusBundle\Service\QuestionAnswerHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckRepositoryStatusCommand extends Command
{
    protected static string $defaultName = 'app:check-repository-status';

    public function __construct(
        private QuestionAsker $questionAsker,
        private QuestionAnswerHandler $questionAnswerHandler
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
        $questionHelper = $this->getHelper('question');

        // @todo use DI
         $collector = new \App\RepoStatusBundle\Service\QuestionCollector([
            new \App\RepoStatusBundle\Question\ConfirmRepoCheckQuestion('test', 'test'),
            new \App\RepoStatusBundle\Question\TimePeriodQuestion(),
            new \App\RepoStatusBundle\Question\GetCountPRsQuestion(),
            new \App\RepoStatusBundle\Question\GetCountCommitsQuestion(),
            new \App\RepoStatusBundle\Question\GenerateSlackReportQuestion(),
            new \App\RepoStatusBundle\Question\PublishToSlackQuestion(),
         ]);

        $questions = $collector->collect();

        $responses = $this->questionAsker->askQuestions($questions, $questionHelper, $input, $output);

        $this->questionAnswerHandler->handleResponses($responses, $output);

        return Command::SUCCESS;
    }
}
