<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Collection\ResponseCollection;

/**
 * @template T
 */
interface ReportGeneratorInterface
{
    /**
     * @param ResponseCollection<T> $responses
     * @return string
     */
    public function generateReportMessage(ResponseCollection $responses): string;
}
