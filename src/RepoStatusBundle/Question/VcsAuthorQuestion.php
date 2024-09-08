<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Exception\OperationCancelledException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

/**
 * @implements QuestionInterface<string>
 */
#[AsTaggedItem(index: 'app.question', priority: 180)]
class VcsAuthorQuestion implements QuestionInterface
{
    public function __construct(
        private readonly GitHubQueryParams $gitHubQueryParams,
        private readonly string $defaultVcsAuthor
    ) {
    }

    public function getKey(): string
    {
        return 'confirm_vcs_author';
    }

    public function createQuestion(): Question
    {
        $questionText = $this->defaultVcsAuthor
            ? sprintf(
                "The default VCS author is \033[1m%s\033[0m. Do you want to use this author? (leave blank to confirm or enter a different username): ",
                $this->defaultVcsAuthor
            )
            : "No default VCS author is defined. Please enter a VCS username: ";

        return new Question($questionText, $this->defaultVcsAuthor ?: null);
    }

    /**
     * @param string $response
     * @param ResponseCollection<string> $responses
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws OperationCancelledException if the user cancels the operation.
     */
    public function handleResponse($response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): void
    {
        if (empty($response)) {
            throw new OperationCancelledException();
        }

        $responses->addResponse($this->getKey(), $response, $this);

        $this->gitHubQueryParams->setAuthor($response);
    }

    public function getReportData(): ?string
    {
        return sprintf('Author: %s', $this->gitHubQueryParams->getAuthor());
    }
}
