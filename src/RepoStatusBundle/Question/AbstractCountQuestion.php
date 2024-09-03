<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Service\RepositoryStatusChecker;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * @implements QuestionInterface<bool>
 */
abstract class AbstractCountQuestion implements QuestionInterface
{
    private int $count = 0;

    public function __construct(
        protected readonly RepositoryStatusChecker $statusChecker,
        protected readonly GitHubQueryParams $gitHubQueryParams
    ) {
    }

    abstract protected function getCount(): int;
    abstract protected function getMessage(): string;
    abstract public function getKey(): string;

    public function createQuestion(): ConfirmationQuestion
    {
        return new ConfirmationQuestion($this->getMessage(), true);
    }

    /**
     * Handle the response for the confirmation question.
     *
     * @param bool $response The response provided by the user.
     * @param ResponseCollection<bool> $responses The collection of responses.
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handleResponse($response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): void
    {
        if ($response === true) {
            $this->count = $this->getCount();
            $output->writeln((string) $this->getReportData());
            $responses->addResponse($this->getKey(), $response, $this);
        }
    }

    public function getReportData(): ?string
    {
        return sprintf('%s: %d', ucfirst(str_replace('_', ' ', $this->getKey())), $this->count);
    }
}
