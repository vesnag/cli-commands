<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Yaml\Yaml;
use InvalidArgumentException;
use App\Config\RepositoryConfig;
use App\Config\NotificationConfig;

class ConfigurationService
{
    private RepositoryConfig $repositoryConfig;
    private NotificationConfig $notificationConfig;

    public function __construct(string $filePath)
    {
        $config = Yaml::parseFile($filePath);

        if (!is_array($config)) {
            throw new InvalidArgumentException('Invalid configuration file format.');
        }

        if (
            !isset($config['repository']) || !is_array($config['repository']) ||
            !isset($config['repository']['type'], $config['repository']['token'], $config['repository']['repo'])
        ) {
            throw new InvalidArgumentException('Invalid repository configuration format.');
        }

        if (
            !isset($config['notification']) || !is_array($config['notification']) ||
            !isset($config['notification']['type'], $config['notification']['webhook_url'])
        ) {
            throw new InvalidArgumentException('Invalid notification configuration format.');
        }

        if (!is_string($config['repository']['type']) || !is_string($config['repository']['token']) || !is_string($config['repository']['repo'])) {
            throw new InvalidArgumentException('Invalid repository configuration values.');
        }

        if (!is_string($config['notification']['type']) || !is_string($config['notification']['webhook_url'])) {
            throw new InvalidArgumentException('Invalid notification configuration values.');
        }

        $this->repositoryConfig = new RepositoryConfig(
            $config['repository']['type'],
            $config['repository']['token'],
            $config['repository']['repo']
        );

        $this->notificationConfig = new NotificationConfig(
            $config['notification']['type'],
            $config['notification']['webhook_url']
        );
    }

    public function getRepositoryConfig(): RepositoryConfig
    {
        return $this->repositoryConfig;
    }

    public function getNotificationConfig(): NotificationConfig
    {
        return $this->notificationConfig;
    }
}
