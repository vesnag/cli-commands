<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\Utils\ConvertTo;

final class ReportGenerator implements ReportGeneratorInterface
{
    /**
     * Generates a report message based on the responses collected.
     *
     * @param ResponseCollection $responses
     * @return string
     */
    public function generateReportMessage(ResponseCollection $responses): string
    {
        $reportLines = [];

        foreach ($responses->all() as $key => $response) {
            $question = $responses->getQuestion($key);
            $reportData = $question ? $question->getReportData() : ConvertTo::string($response, $key);

            $reportLines[] = sprintf(
                "*%s:* %s",
                ucfirst(str_replace('_', ' ', $key)),
                $reportData
            );
        }

        return implode("\n", $reportLines);
    }
}
