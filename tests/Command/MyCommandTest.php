<?php

namespace App\Tests\Command;

use App\Command\MyCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class MyCommandTest extends TestCase
{
    public function testExecute()
    {
        $command = new MyCommand();

        $commandTester = new CommandTester($command);

        $commandTester->execute([]);

        $this->assertStringContainsString('Running my custom command...', $commandTester->getDisplay());
    }
}
