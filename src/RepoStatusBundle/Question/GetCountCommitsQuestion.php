<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 65)]
final class GetCountCommitsQuestion extends AbstractCountQuestion
{
    public function getKey(): string
    {
        return 'get_count_commits';
    }

    protected function getCount(): int
    {
        return $this->statusChecker->getCommitsCount();
    }

    protected function getMessage(): string
    {
        return 'Do you want to get the number of commits? (yes/no) [yes]';
    }
}
