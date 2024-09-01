<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

interface QuestionInterface
{
    public function getKey(): string;
    public function createQuestion(): Question;

    /**
     * Validate and process the response after the question is asked.
     *
     * @param mixed $response The response provided by the user.
     * @param ResponseCollection $responses The collection of all collected responses.
     * @param InputInterface $input The input interface.
     * @param OutputInterface $output The output interface.
     * @return mixed Processed response, or any action based on the response.
     */
    public function handleResponse(mixed $response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): mixed;

    /**
     * Get the report data as a formatted string.
     *
     * @return ?string The formatted report data, or null if no data should be included in the report.
     */
    public function getReportData(): ?string;
}
