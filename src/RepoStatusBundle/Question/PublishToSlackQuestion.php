<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

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
}
