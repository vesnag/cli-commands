<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-repository-status',
    description: 'Gets the number of pull requests created today from a GitHub repository.',
)]
class CheckRepositoryStatusCommand extends Command
{
    protected static string $defaultName = 'app:check-repository-status';

    protected function configure()
    {
        $this
            ->setDescription('Checks the status of the repository and sends a notification.')
            ->setHelp('This command checks the status of the repository and sends a notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $status = 'TODO';
        $output->writeln('Repository status: ' . $status);

        return Command::SUCCESS;
    }
}
