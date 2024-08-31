<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class TimePeriodQuestion implements QuestionInterface
{
    public function getKey(): string
    {
        return 'time';
    }

    public function createQuestion(): Question
    {
        return new ChoiceQuestion(
            'Select time period [entire history]',
            ['today', 'this week', 'entire history'],
            2
        );
    }
}
