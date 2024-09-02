<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Collection;

use IteratorAggregate;
use ArrayIterator;
use InvalidArgumentException;
use App\RepoStatusBundle\Question\QuestionInterface;

/**
 * @template T
 * @implements IteratorAggregate<string, array{question: QuestionInterface<T>, response: T}>
 */
class ResponseCollection implements IteratorAggregate
{
    /**
     * @var array<string, array{question: QuestionInterface<T>, response: T}>
     */
    private array $responses = [];

    /**
     * Add a response to the collection.
     *
     * @param string $key The key identifying the question.
     * @param T $response The response provided by the user.
     * @param QuestionInterface<T> $question The question instance.
     */
    public function addResponse(string $key, $response, QuestionInterface $question): void
    {
        if (isset($this->responses[$key])) {
            throw new InvalidArgumentException("Response for key '{$key}' already exists.");
        }

        $this->responses[$key] = ['question' => $question, 'response' => $response];
    }

    /**
     * Get a response by key.
     *
     * @param string $key
     * @return T|null
     */
    public function getResponse(string $key)
    {
        return $this->responses[$key]['response'] ?? null;
    }

    /**
     * Get all responses.
     *
     * @return array<string, T>
     */
    public function all(): array
    {
        $allResponses = [];
        foreach ($this->responses as $key => $response) {
            $allResponses[$key] = $response['response'];
        }
        return $allResponses;
    }

    /**
     * @return ArrayIterator<string, array{question: QuestionInterface<T>, response: T}>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->responses);
    }
}
