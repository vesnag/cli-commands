<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Collector;

use App\RepoStatusBundle\Question\QuestionInterface;

class QuestionCollector
{
    /**
     * @var QuestionInterface[]
     */
    private array $questions;

    /**
     * @param iterable<QuestionInterface> $questions
     */
    public function __construct(iterable $questions)
    {
        $this->questions = is_array($questions) ? $questions : iterator_to_array($questions);
    }

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
}
