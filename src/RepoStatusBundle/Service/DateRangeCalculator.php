<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

class DateRangeCalculator
{
    /**
     * Calculates the start and end date based on the given time period.
     *
     * @param string|null $timePeriodResponse
     * @return array{string|null, string|null}
     */
    public function calculateDateRange(?string $timePeriodResponse): array
    {
        if ($timePeriodResponse === 'today') {
            $date = (new \DateTime())->format('Y-m-d');
            return [$date, $date];
        }

        if ($timePeriodResponse === 'this week') {
            $startDate = (new \DateTime())->modify('this week')->format('Y-m-d');
            $endDate = (new \DateTime())->modify('this week +6 days')->format('Y-m-d');
            return [$startDate, $endDate];
        }

        return [null, null];
    }
}
