<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Question\QuestionInterface;
use App\RepoStatusBundle\Service\ReportGeneratorInterface;

/**
 * @template T
 * @implements ReportGeneratorInterface<T>
 */
class ReportGenerator implements ReportGeneratorInterface
{
    /**
     * @param ResponseCollection<T> $responses
     * @return string
     */
    public function generateReportMessage(ResponseCollection $responses): string
    {
        $separator = str_repeat('-', 80) . PHP_EOL;
        $report = PHP_EOL . $separator;

        $reportData = array_map(function ($response) {
            /** @var QuestionInterface<T> $question */
            $question = $response['question'];
            return $question->getReportData();
        }, iterator_to_array($responses));

        $report .= implode(PHP_EOL, $reportData);
        $report = rtrim($report) . PHP_EOL . $separator;

        return $report;
    }
}
