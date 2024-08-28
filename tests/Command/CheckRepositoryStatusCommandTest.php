<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\CheckRepositoryStatusCommand;
use App\Repository\RepositoryInterface;
use App\Notification\NotificationInterface;
use App\Service\ConfigurationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CheckRepositoryStatusCommandTest extends TestCase
{
    public function testExecute()
    {
        $configService = $this->createMock(ConfigurationService::class);
        $repository = $this->createMock(RepositoryInterface::class);
        $notification = $this->createMock(NotificationInterface::class);

        $repository->method('checkStatus')->willReturn('Repository status');
        $notification->method('send')->willReturn(true);

        $command = new CheckRepositoryStatusCommand($configService, $repository, $notification);
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Repository status', $output);
    }
}
