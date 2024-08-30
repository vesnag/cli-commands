<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\DTO\ResponseParams;
use Symfony\Component\Console\Output\OutputInterface;

class ResponseProcessor
{
    public function __construct(
        private RepositoryStatusChecker $statusChecker,
        private MessageGenerator $messageGenerator
    ) {
    }

    public function processResponses(ResponseParams $params, OutputInterface $output): void
    {
        $pullRequests = [];
        if ($params->getPrsResponse) {
            $pullRequests = $this->statusChecker->getPullRequestsForDateRange($params->startDate, $params->endDate);
            $output->writeln('Pull requests for the selected period: ' . count($pullRequests));
        }

        $commitCount = 0;
        if ($params->getCommitsResponse) {
            $commitCount = $this->statusChecker->getCommitCount($params->startDate, $params->endDate);
            $output->writeln('Number of commits: ' . $commitCount);
        }

        if ($params->generateReport) {
            $reportMessage = $this->messageGenerator->generateReportMessage($params->timePeriodResponse, count($pullRequests), $commitCount);
            $output->writeln('Report message:');
            $output->writeln($reportMessage);
        }

        if ($params->publishToSlackResponse) {
            $output->writeln('<comment>Publishing to Slack is not yet supported.</comment>');
        }
    }

}
