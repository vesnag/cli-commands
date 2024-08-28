<?php

declare(strict_types=1);

namespace App\Config;

class NotificationConfig
{
    public string $type;
    public string $webhookUrl;

    public function __construct(string $type, string $webhookUrl)
    {
        $this->type = $type;
        $this->webhookUrl = $webhookUrl;
    }
}
