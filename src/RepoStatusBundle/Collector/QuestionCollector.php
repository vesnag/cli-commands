<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Collector;

use App\RepoStatusBundle\Question\QuestionInterface;

/**
 * @template T
 */
class QuestionCollector
{
    /**
     * @var QuestionInterface<T>[]
     */
    private array $questions;

    /**
     * @param iterable<QuestionInterface<T>> $questions
     */
    public function __construct(iterable $questions)
    {
        $this->questions = is_array($questions) ? $questions : iterator_to_array($questions);
    }

    /**
     * @return QuestionInterface<T>[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
}
