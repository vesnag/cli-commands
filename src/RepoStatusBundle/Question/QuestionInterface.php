<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;

interface QuestionInterface
{
    public function getKey(): string;
    public function createQuestion(): Question;

    /**
     * Get the report data to be included in the final report.
     * Returns a formatted string or null if the question does not contribute to the report.
     */
    public function getReportData(): ?string;
}
