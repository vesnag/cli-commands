<?php

declare(strict_types=1);

namespace App\Notification;

class SlackNotification implements NotificationInterface
{
    public function __construct(
        private array $config,
    ) {
    }

    public function send(string $message)
    {
        // Use Slack API to send message
        return true;
    }
}
