<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MyCommand extends Command
{
    protected static $defaultName = 'app:my-command';

    protected function configure(): void
    {
        $this
        ->setName('app:my-command')
        ->setDescription('My custom CLI command')
        ->setHelp('This command allows you to run specific tasks...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Running my custom command...');
        return Command::SUCCESS;
    }
}
