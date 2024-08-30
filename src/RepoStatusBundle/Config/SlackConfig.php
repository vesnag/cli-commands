<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Config;

class SlackConfig
{
    public function __construct(
        private string $slackBotToken,
        private string $slackChannel
    ) {
    }

    public function getToken(): string
    {
        return $this->slackBotToken;
    }

    public function getChannel(): string
    {
        return $this->slackChannel;
    }
}
