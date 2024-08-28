<?php

declare(strict_types=1);

namespace App\Notification;

class SlackNotification implements NotificationInterface
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        private array $config,
    ) {
    }

    public function send(string $message): bool
    {
        // Use Slack API to send message
        return true;
    }
}
