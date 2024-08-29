<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Command;

use App\RepoStatusBundle\Command\BaseCommand;
use App\RepoStatusBundle\Config\GitHubConfig;
use App\RepoStatusBundle\Service\QuestionAsker;
use App\RepoStatusBundle\Service\QuestionAnswerHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CheckRepositoryStatusCommand extends BaseCommand
{
    protected static string $defaultName = 'app:check-repository-status';

    public const CONFIRM_REPO_CHECK = 'confirm_repo_check';
    public const TIME_PERIOD = 'time';
    public const GET_COUNT_PRS = 'get_count_prs';
    public const GET_COUNT_COMMITS = 'get_count_commits';
    public const GENERATE_SLACK_REPORT = 'generate_slack_report';
    public const PUBLISH_TO_SLACK = 'publish_to_slack';

    public function __construct(
        private GitHubConfig $githubConfig,
        private QuestionAsker $questionAsker,
        private QuestionAnswerHandler $questionAnswerHandler
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Checks the status of the repository and sends a notification.')
            ->setHelp('This command checks the status of the repository and sends a notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $questions = $this->getQuestions();

        $responses = $this->questionAsker->askQuestions($questions, $helper, $input, $output);
        $this->questionAnswerHandler->handleResponses($responses, $output);

        return Command::SUCCESS;
    }

    /**
     * @return array<string, ConfirmationQuestion|ChoiceQuestion>
     */
    protected function getQuestions(): array
    {
        $githubOwner = $this->githubConfig->getOwner();
        $githubRepo = $this->githubConfig->getRepo();

        return [
            self::CONFIRM_REPO_CHECK => new ConfirmationQuestion(
                sprintf(
                    "The command/system will check remote repo \033[1m%s/%s\033[0m. Do you want to continue? (yes/no) [yes]",
                    $githubOwner,
                    $githubRepo
                ),
                true
            ),
            self::TIME_PERIOD => new ChoiceQuestion(
                'Select time period',
                ['today', 'this week', 'entire history'],
                0
            ),
            self::GET_COUNT_PRS => new ConfirmationQuestion('Do you want to get number of PRs? (yes/no) [yes]', true),
            self::GET_COUNT_COMMITS => new ConfirmationQuestion('Do you want to get number of commits? (yes/no) [yes]', true),
            self::GENERATE_SLACK_REPORT => new ConfirmationQuestion('Do you want to generate a report for Slack? (yes/no) [yes]', true),
            self::PUBLISH_TO_SLACK => new ConfirmationQuestion('Do you want to publish this status to Slack? (yes/no) [no]', false),
        ];
    }
}
