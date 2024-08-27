<?php

declare(strict_types=1);

namespace App\Service;

class ConfigurationFactory
{
    private ConfigurationService $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
    }

    public function createRepositoryConfig()
    {
        return $this->configService->getRepositoryConfig();
    }

    public function createNotificationConfig()
    {
        return $this->configService->getNotificationConfig();
    }
}
