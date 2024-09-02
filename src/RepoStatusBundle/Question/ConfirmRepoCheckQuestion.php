<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Exception\OperationCancelledException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 200)]
class ConfirmRepoCheckQuestion implements QuestionInterface
{
    private const GITHUB_URL_FORMAT = 'https://github.com/%s/%s';

    public function __construct(
        private readonly string $owner,
        private readonly string $repo,
    ) {
    }

    public function getKey(): string
    {
        return 'confirm_repo_check';
    }

    public function createQuestion(): Question
    {
        return new ConfirmationQuestion(
            sprintf(
                "The command/system will check remote repo \033[1m%s/%s\033[0m. Do you want to continue? (yes/no) [yes]",
                $this->owner,
                $this->repo
            ),
            true
        );
    }

    public function handleResponse(mixed $response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): mixed
    {

        if (false === $response) {
            $output->writeln('<comment>Operation cancelled by user.</comment>');
            throw new OperationCancelledException();
        }

        $responses->addResponse($this->getKey(), $response, $this);

        return $response;
    }

    public function getReportData(): ?string
    {
        return sprintf('Report for repository: %s', $this->getRepositoryLink());
    }

    public function getRepositoryLink(): string
    {
        return sprintf(self::GITHUB_URL_FORMAT, $this->owner, $this->repo);
    }
}
