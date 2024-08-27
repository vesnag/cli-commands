<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ConfigurationService;
use App\Repository\GitHubRepository;
use App\Notification\SlackNotification;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckRepositoryStatusCommand extends Command
{
    protected static $defaultName = 'app:check-repository-status';

    public function __construct(
        private ConfigurationService $configService,
        private GitHubRepository $repository,
        private SlackNotification $notification
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Checks the status of the repository and sends a notification.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Your command logic here

        return Command::SUCCESS;
    }
}
