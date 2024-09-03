<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Service\MessageSender\MessageSender;
use Symfony\Component\Console\Output\OutputInterface;

class SlackPublisher
{
    public function __construct(private MessageSender $messageSender) {}

    public function publishToSlack(string $reportMessage, OutputInterface $output): void
    {
        $sendMessageSuccess = $this->messageSender->sendMessage($reportMessage);
        $message = $sendMessageSuccess ? 'Message successfully posted to Slack.' : 'Failed to post message to Slack.';
        $output->writeln($message);
    }
}
