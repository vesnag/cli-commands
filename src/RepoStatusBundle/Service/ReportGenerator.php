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
        $report = '';

        foreach ($responses as $response) {
            /** @var QuestionInterface<T> $question */
            $question = $response['question'];
            $report .= $question->getReportData() . PHP_EOL;
        }

        return $report;
    }
}
