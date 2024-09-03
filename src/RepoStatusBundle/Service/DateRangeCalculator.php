<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Question\TimePeriodQuestion;

final class DateRangeCalculator
{
    /**
     * Calculates the start and end date based on the given time period.
     *
     * @param string|null $timePeriodResponse
     * @return array{string|null, string|null}
     */
    public function calculateDateRange(?string $timePeriodResponse): array
    {
        $now = new \DateTime();
        return match ($timePeriodResponse) {
            TimePeriodQuestion::LAST_24_HOURS => [
                $now->modify('-24 hours')->format(\DateTime::ATOM),
                (new \DateTime())->format(\DateTime::ATOM)
            ],
            TimePeriodQuestion::LAST_7_DAYS => [
                $now->modify('-7 days')->format(\DateTime::ATOM),
                (new \DateTime())->format(\DateTime::ATOM)
            ],
            TimePeriodQuestion::LAST_30_DAYS => [
                $now->modify('-30 days')->format(\DateTime::ATOM),
                (new \DateTime())->format(\DateTime::ATOM)
            ],
            default => [null, null],
        };
    }
}
