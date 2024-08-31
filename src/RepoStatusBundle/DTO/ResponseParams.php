<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\DTO;

class ResponseParams
{
    public function __construct(
        public readonly ?string $startDate,
        public readonly ?string $endDate,
        public readonly string $timePeriodResponse,
        public readonly string $getPrsResponse,
        public readonly string $getCommitsResponse,
        public readonly string $publishToSlackResponse
    ) {
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function getTimePeriodResponse(): string
    {
        return $this->timePeriodResponse;
    }

    public function getPrsResponse(): string
    {
        return $this->getPrsResponse;
    }

    public function getCommitsResponse(): string
    {
        return $this->getCommitsResponse;
    }

    public function getPublishToSlackResponse(): string
    {
        return $this->publishToSlackResponse;
    }
}
