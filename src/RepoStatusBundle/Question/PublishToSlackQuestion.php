<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 60)]
class PublishToSlackQuestion implements QuestionInterface
{
    public function getKey(): string
    {
        return 'publish_to_slack';
    }

    public function createQuestion(): Question
    {
        return new ConfirmationQuestion('Do you want to publish this status to Slack? (yes/no) [no]', false);
    }

    public function getReportData(): ?string
    {
        return null;
    }
}
