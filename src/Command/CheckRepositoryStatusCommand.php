<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ConfigurationService;
use App\Repository\RepositoryInterface;
use App\Notification\NotificationInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckRepositoryStatusCommand extends Command
{
    protected static $defaultName = 'app:check-repo-status';

    public function __construct(
        private ConfigurationService $configService,
        private RepositoryInterface $repository,
        private NotificationInterface $notification
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Checks the status of a repository and sends a notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $status = $this->repository->checkStatus();
        $this->notification->send($status);

        $output->writeln($status);

        return Command::SUCCESS;
    }
}
