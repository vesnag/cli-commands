<?php

declare(strict_types=1);

namespace App\Notification;

class SlackNotification implements NotificationInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function send(string $message)
    {
        // Use Slack API to send message
        return true;
    }
}
