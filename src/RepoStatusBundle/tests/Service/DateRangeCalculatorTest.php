<?php

declare(strict_types=1);

namespace App\Tests\RepoStatusBundle\Service;

use App\RepoStatusBundle\Service\DateRangeCalculator;
use App\RepoStatusBundle\Question\TimePeriodQuestion;
use PHPUnit\Framework\TestCase;

class DateRangeCalculatorTest extends TestCase
{
    private DateRangeCalculator $dateRangeCalculator;

    protected function setUp(): void
    {
        $this->dateRangeCalculator = new DateRangeCalculator();
    }

    public function testCalculateDateRangeLast24Hours(): void
    {
        $result = $this->dateRangeCalculator->calculateDateRange(TimePeriodQuestion::LAST_24_HOURS);
        $expectedSince = (new \DateTime())->modify('-24 hours')->format(\DateTime::ATOM);
        $expectedUntil = (new \DateTime())->format(\DateTime::ATOM);

        $this->assertEquals($expectedSince, $result[0]);
        $this->assertEquals($expectedUntil, $result[1]);
    }

    public function testCalculateDateRangeLast7Days(): void
    {
        $result = $this->dateRangeCalculator->calculateDateRange(TimePeriodQuestion::LAST_7_DAYS);
        $expectedSince = (new \DateTime())->modify('-7 days')->format(\DateTime::ATOM);
        $expectedUntil = (new \DateTime())->format(\DateTime::ATOM);

        $this->assertEquals($expectedSince, $result[0]);
        $this->assertEquals($expectedUntil, $result[1]);
    }

    public function testCalculateDateRangeLast30Days(): void
    {
        $result = $this->dateRangeCalculator->calculateDateRange(TimePeriodQuestion::LAST_30_DAYS);
        $expectedSince = (new \DateTime())->modify('-30 days')->format(\DateTime::ATOM);
        $expectedUntil = (new \DateTime())->format(\DateTime::ATOM);

        $this->assertEquals($expectedSince, $result[0]);
        $this->assertEquals($expectedUntil, $result[1]);
    }

    public function testCalculateDateRangeDefault(): void
    {
        $result = $this->dateRangeCalculator->calculateDateRange(null);

        $this->assertNull($result[0]);
        $this->assertNull($result[1]);
    }
}
