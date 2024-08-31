<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 100)]
class ConfirmRepoCheckQuestion implements QuestionInterface
{
    private string $owner;
    private string $repo;
    private bool $confirmed;

    public function __construct(string $owner, string $repo)
    {
        $this->owner = $owner;
        $this->repo = $repo;
        $this->confirmed = false; // Default to false until confirmed by user
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

    public function setConfirmed(bool $confirmed): void
    {
        $this->confirmed = $confirmed;
    }

    public function getReportData(): ?string
    {
        return sprintf(
            'Repository check for %s/%s was %s.',
            $this->owner,
            $this->repo,
            $this->confirmed ? 'confirmed' : 'not confirmed'
        );
    }
}
