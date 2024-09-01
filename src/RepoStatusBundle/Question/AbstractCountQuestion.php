<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Service\RepositoryStatusChecker;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

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

    public function handleResponse(mixed $response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): mixed
    {
        if ($response === true) {
            $this->count = $this->getCount();
            $output->writeln((string) $this->getReportData());
            $responses->addResponse($this->getKey(), $response, $this);
        }

        return $response;
    }

    public function getReportData(): ?string
    {
        return sprintf('%s: %d', ucfirst(str_replace('_', ' ', $this->getKey())), $this->count);
    }
}
