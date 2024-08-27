<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class ConfigurationService
{
    private $config;

    public function __construct(string $filePath)
    {
        $this->config = Yaml::parseFile($filePath);
    }

    public function getRepositoryConfig(): array
    {
        return $this->config['repository'];
    }

    public function getNotificationConfig(): array
    {
        return $this->config['notification'];
    }
}
