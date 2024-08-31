<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ConfirmRepoCheckQuestion implements QuestionInterface
{
    private string $owner;
    private string $repo;

    public function __construct(string $owner, string $repo)
    {
        $this->owner = $owner;
        $this->repo = $repo;
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
}
