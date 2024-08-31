<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 10)]
class GenerateSlackReportQuestion implements QuestionInterface
{
    public function getKey(): string
    {
        return 'generate_slack_report';
    }

    public function createQuestion(): Question
    {
        return new ConfirmationQuestion('Do you want to generate a report, message for Slack? (yes/no) [yes]', true);
    }
}
