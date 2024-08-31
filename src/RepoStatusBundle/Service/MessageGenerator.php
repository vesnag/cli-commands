<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

class MessageGenerator
{
    /**
     * Generates a report message based on the provided data.
     *
     * @param array<string, mixed> $reportData An associative array where keys are labels and values are the corresponding data.
     * @return string The formatted report message.
     */
    public function generateReportMessage(array $reportData): string
    {
        $reportLines = [];

        foreach ($reportData as $label => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $reportLines[] = sprintf("*%s:* %s", ucfirst($label), $value);
        }

        return implode("\n", $reportLines);
    }
}
