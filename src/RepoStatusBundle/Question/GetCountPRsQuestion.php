<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 70)]
final class GetCountPRsQuestion extends AbstractCountQuestion
{
    public function getKey(): string
    {
        return 'get_count_prs';
    }

    protected function getCount(): int
    {
        return count($this->statusChecker->getPullRequestsForDateRange());
    }

    protected function getMessage(): string
    {
        return 'Do you want to get the number of PRs? (yes/no) [yes]';
    }
}
