<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\DTO\ResponseParams;
use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use Symfony\Component\Console\Output\OutputInterface;

class ResponseProcessor
{
    public function __construct(
        private readonly RepositoryStatusChecker $statusChecker,
        private readonly MessageGenerator $messageGenerator,
        private readonly MessageSender $messageSender
    ) {
    }

    public function processResponses(ResponseParams $params, OutputInterface $output): void
    {
        $pullRequests = $this->getPullRequestsIfRequested($params, $output);
        $commitCount = $this->getCommitsIfRequested($params, $output);

        $reportData = [
            'time period' => $params->getTimePeriodResponse(),
            'number of pull requests' => count($pullRequests),
            'number of commits' => $commitCount,
            // You can add more dynamic data here as new questions or metrics are added
        ];

        $reportMessage = $this->messageGenerator->generateReportMessage($reportData);
        $output->writeln('Report message:');
        $output->writeln($reportMessage);

        if ($params->getPublishToSlackResponse()) {
            $this->publishReportToSlack($reportMessage, $output);
        }
    }

    private function getPullRequestsIfRequested(ResponseParams $params, OutputInterface $output): array
    {
        if ($params->getPrsResponse()) {
            $pullRequests = $this->statusChecker->getPullRequestsForDateRange($params->getStartDate(), $params->getEndDate());
            $output->writeln('Pull requests for the selected period: ' . count($pullRequests));
            return $pullRequests;
        }

        return [];
    }

    private function getCommitsIfRequested(ResponseParams $params, OutputInterface $output): int
    {
        if ($params->getCommitsResponse()) {
            $commitCount = $this->statusChecker->getCommitCount($params->getStartDate(), $params->getEndDate());
            $output->writeln('Number of commits: ' . $commitCount);
            return $commitCount;
        }

        return 0;
    }

    private function publishReportToSlack(string $reportMessage, OutputInterface $output): void
    {
        $sendMessageResponse = $this->messageSender->sendMessage($reportMessage);

        if ($sendMessageResponse['success']) {
            $output->writeln('Message successfully posted to Slack.');
            return;
        }

        $output->writeln('Failed to post message to Slack.');
    }
}
