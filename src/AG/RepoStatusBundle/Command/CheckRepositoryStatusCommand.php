<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Command;

use AG\RepoStatusBundle\Service\RepositoryStatusChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckRepositoryStatusCommand extends Command
{
    protected static string $defaultName = 'app:check-repository-status';

    private RepositoryStatusChecker $statusChecker;

    public function __construct(RepositoryStatusChecker $statusChecker)
    {
        parent::__construct();
        $this->statusChecker = $statusChecker;
    }

    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Checks the status of the repository and sends a notification.')
            ->setHelp('This command checks the status of the repository and sends a notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $openPullRequestsCount = $this->statusChecker->getOpenPullRequestsCount();
        $output->writeln('Open pull requests: ' . $openPullRequestsCount);

        return Command::SUCCESS;
    }
}
