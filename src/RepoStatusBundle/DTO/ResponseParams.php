<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\DTO;

class ResponseParams
{
    public function __construct(
        public ?string $startDate,
        public ?string $endDate,
        public string $timePeriodResponse,
        public string $getPrsResponse,
        public string $getCommitsResponse,
        public string $generateReport,
        public string $publishToSlackResponse
    ) {
    }
}
