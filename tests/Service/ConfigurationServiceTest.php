<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\ConfigurationService;
use PHPUnit\Framework\TestCase;

class ConfigurationServiceTest extends TestCase
{
    public function testGetRepositoryConfig()
    {
        $configService = new ConfigurationService(__DIR__ . '/../../config/config.yaml');
        $repoConfig = $configService->getRepositoryConfig();

        $this->assertArrayHasKey('type', $repoConfig);
        $this->assertArrayHasKey('token', $repoConfig);
        $this->assertArrayHasKey('repo', $repoConfig);
    }

    public function testGetNotificationConfig()
    {
        $configService = new ConfigurationService(__DIR__ . '/../../config/config.yaml');
        $notificationConfig = $configService->getNotificationConfig();

        $this->assertArrayHasKey('type', $notificationConfig);
        $this->assertArrayHasKey('webhook_url', $notificationConfig);
    }
}
