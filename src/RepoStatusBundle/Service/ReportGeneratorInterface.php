<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Collection\ResponseCollection;

interface ReportGeneratorInterface
{
    public function generateReportMessage(ResponseCollection $responses): string;
}
