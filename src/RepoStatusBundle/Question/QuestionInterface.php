<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\RepoStatusBundle\Collection\ResponseCollection;

/**
 * @template T
 */
interface QuestionInterface
{
    /**
     * Get the key for the question.
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Create the question.
     *
     * @return \Symfony\Component\Console\Question\Question
     */
    public function createQuestion(): \Symfony\Component\Console\Question\Question;

    /**
     * Handle the response for the question.
     *
     * @param T $response
     * @param ResponseCollection<T> $responses
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return T
     */
    public function handleResponse($response, ResponseCollection $responses, InputInterface $input, OutputInterface $output);

    /**
     * Get the report data for the question.
     *
     * @return string|null
     */
    public function getReportData(): ?string;
}
