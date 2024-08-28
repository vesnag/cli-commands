<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\NotificationConfig;
use App\Config\RepositoryConfig;

class ConfigurationFactory
{
    public function __construct(
        private ConfigurationService $configService
    ) {
    }

    public function createRepositoryConfig(): RepositoryConfig
    {
        return $this->configService->getRepositoryConfig();
    }

    public function createNotificationConfig(): NotificationConfig
    {
        return $this->configService->getNotificationConfig();
    }
}
