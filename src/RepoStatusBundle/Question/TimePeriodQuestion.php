<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Service\DateRangeCalculator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

/**
 * @implements QuestionInterface<string>
 */
#[AsTaggedItem(index: 'app.question', priority: 95)]
class TimePeriodQuestion implements QuestionInterface
{
    public const LAST_24_HOURS = 'Last 24 Hours';
    public const LAST_7_DAYS = 'Last 7 Days';
    public const LAST_30_DAYS = 'Last 30 Days';

    private string $selectedPeriod;

    public function __construct(
        private readonly DateRangeCalculator $dateRangeCalculator,
        private readonly GitHubQueryParams $gitHubQueryParams,
    ) {
    }

    public function getKey(): string
    {
        return 'time_period';
    }

    public function createQuestion(): Question
    {
        return new ChoiceQuestion(
            'Please select the time period:',
            [
                self::LAST_24_HOURS,
                self::LAST_7_DAYS,
                self::LAST_30_DAYS
            ],
            0
        );
    }

    /**
     * Handle the response for the time period question.
     *
     * @param string $response
     * @param ResponseCollection<string> $responses
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handleResponse($response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): void
    {
        $this->selectedPeriod = $response;
        $responses->addResponse($this->getKey(), $response, $this);

        [$since, $until] = $this->dateRangeCalculator->calculateDateRange($response);

        $this->gitHubQueryParams->setSince($since)->setUntil($until);
    }

    public function getSelectedPeriod(): string
    {
        return $this->selectedPeriod;
    }

    public function getReportData(): ?string
    {
        return sprintf('Selected time period: %s', $this->selectedPeriod);
    }
}
