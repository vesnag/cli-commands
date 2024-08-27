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

    public function createRepositoryConfig(): array
    {
        return $this->configService->getRepositoryConfig();
    }

    public function createNotificationConfig(): array
    {
        return $this->configService->getNotificationConfig();
    }
}
