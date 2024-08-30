<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

class MessageGenerator
{
    public function generateReportMessage(string $timePeriod, int $pullRequestCount, int $commitCount): string
    {
        return sprintf(
            "*Report for %s:*\n- *Number of pull requests:* %d\n- *Number of commits:* %d",
            $timePeriod,
            $pullRequestCount,
            $commitCount
        );
    }
}
