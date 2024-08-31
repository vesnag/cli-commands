<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 65)]
class GetCountCommitsQuestion implements QuestionInterface
{

    private int $commitCount;

    public function getKey(): string
    {
        return 'get_count_commits';
    }

    public function createQuestion(): Question
    {
        return new ConfirmationQuestion('Do you want to get number of commits? (yes/no) [yes]', true);
    }

    public function setCommitCount(int $count): void
    {
        $this->commitCount = $count;
    }

    public function getReportData(): ?string
    {
        return sprintf('Number of commits: %d', $this->commitCount);
    }
}
