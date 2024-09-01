<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Collection;

use App\RepoStatusBundle\Question\QuestionInterface;
use InvalidArgumentException;

class ResponseCollection
{
    /**
     * @var array<string, mixed>
     */
    private array $responses = [];

    /**
     * @var array<string, QuestionInterface>
     */
    private array $questions = [];

    /**
     * Add a response to the collection.
     *
     * @param string $key The key identifying the question.
     * @param mixed $response The response provided by the user.
     * @param QuestionInterface $question The question instance.
     */
    public function addResponse(string $key, mixed $response, QuestionInterface $question): void
    {
        if (isset($this->responses[$key])) {
            throw new InvalidArgumentException("Response for key '{$key}' already exists.");
        }

        $this->responses[$key] = $response;
        $this->questions[$key] = $question;
    }

    /**
     * Get a response from the collection.
     *
     * @param string $key The key identifying the question.
     * @return mixed The response, or null if not found.
     */
    public function getResponse(string $key): mixed
    {
        return $this->responses[$key] ?? null;
    }

    /**
     * Get a question from the collection.
     *
     * @param string $key The key identifying the question.
     * @return QuestionInterface|null The question, or null if not found.
     */
    public function getQuestion(string $key): ?QuestionInterface
    {
        return $this->questions[$key] ?? null;
    }

    /**
     * Check if a response exists for a given key.
     *
     * @param string $key The key identifying the question.
     * @return bool True if the response exists, false otherwise.
     */
    public function hasResponse(string $key): bool
    {
        return isset($this->responses[$key]);
    }

    /**
     * Get all responses as an associative array.
     *
     * @return array<string, mixed> The array of all responses.
     */
    public function all(): array
    {
        return $this->responses;
    }
}
