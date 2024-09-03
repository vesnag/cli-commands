<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Collector;

use App\RepoStatusBundle\Question\QuestionInterface;
use InvalidArgumentException;

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

    /**
     * Get a question by its key.
     *
     * @param string $key
     * @return QuestionInterface<T>
     * @throws InvalidArgumentException if the question with the given key is not found.
     */
    public function getQuestionByKey(string $key): QuestionInterface
    {
        foreach ($this->questions as $question) {
            if ($question->getKey() === $key) {
                return $question;
            }
        }

        throw new InvalidArgumentException(sprintf('Question with key "%s" not found.', $key));
    }
}
