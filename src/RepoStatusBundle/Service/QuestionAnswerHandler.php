<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
use Symfony\Component\Console\Output\OutputInterface;

class QuestionAnswerHandler
{
    public function __construct(
        private RepositoryStatusChecker $statusChecker
    ) {
    }

    /**
     * @param array<string, mixed> $responses
     */
    public function handleResponses(array $responses, OutputInterface $output): void
    {
        $timePeriodResponse = (string) $responses[CheckRepositoryStatusCommand::TIME_PERIOD];
        $getPrsResponse = (string) $responses[CheckRepositoryStatusCommand::GET_COUNT_PRS];
        $getCommitsResponse = (string) $responses[CheckRepositoryStatusCommand::GET_COUNT_COMMITS];
        $generateSlackReport = (string) $responses[CheckRepositoryStatusCommand::GENERATE_SLACK_REPORT];
        $publishToSlackResponse = (string) $responses[CheckRepositoryStatusCommand::PUBLISH_TO_SLACK];

        $startDate = null;
        $endDate = null;

        if ($timePeriodResponse === 'today') {
            $startDate = $endDate = (new \DateTime())->format('Y-m-d');
        } elseif ($timePeriodResponse === 'this week') {
            $startDate = (new \DateTime())->modify('this week')->format('Y-m-d');
            $endDate = (new \DateTime())->modify('this week +6 days')->format('Y-m-d');
        }

        $pullRequests = 0;
        if ($getPrsResponse) {
            $pullRequests = $this->statusChecker->getPullRequestsForDateRange($startDate, $endDate);
            $output->writeln('Pull requests for the selected period: ' . count($pullRequests));
        }

        $commitCount = 0;
        if ($getCommitsResponse) {
            $commitCount = $this->statusChecker->getCommitCount($startDate, $endDate);
            $output->writeln('Number of commits: ' . $commitCount);
        }

        if ($generateSlackReport) {
            $slackMessage = $this->generateSlackMessage($timePeriodResponse, count($pullRequests), $commitCount);
            $output->writeln('Slack message:');
            $output->writeln($slackMessage);
        }

        if ($publishToSlackResponse) {
            $output->writeln('<comment>Publishing to Slack is not yet supported.</comment>');
        }
    }

    private function generateSlackMessage(string $timePeriod, int $pullRequestCount, int $commitCount): string
    {
        return sprintf(
            "*Report for %s:*\n- *Number of pull requests:* %d\n- *Number of commits:* %d",
            $timePeriod,
            $pullRequestCount,
            $commitCount
        );
    }
}
