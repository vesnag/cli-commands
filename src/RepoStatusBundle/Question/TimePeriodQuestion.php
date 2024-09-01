<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\DTO\GitHubQueryParams;
use App\RepoStatusBundle\Service\DateRangeCalculator;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: 95)]
class TimePeriodQuestion implements QuestionInterface
{
    private string $selectedPeriod;

    public function __construct(
        private GitHubQueryParams $gitHubQueryParams,
        private DateRangeCalculator $dateRangeCalculator
    ) {
    }

    public function getKey(): string
    {
        return 'time';
    }

    public function createQuestion(): Question
    {
        return new ChoiceQuestion(
            'Select time period [entire history]',
            ['today', 'this week', 'entire history'],
            2
        );
    }

    public function setSelectedPeriod(string $period): void
    {
        $this->selectedPeriod = $period;
    }

    public function handleResponse(mixed $response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): mixed
    {
        if (!in_array($response, ['today', 'this week', 'entire history'])) {
            throw new RuntimeException('Invalid time period selected.');
        }

        [$startDate, $endDate] = $this->dateRangeCalculator->calculateDateRange($response);

        // Set the calculated dates in GitHubQueryParams
        $this->gitHubQueryParams
            ->setSince($startDate)
            ->setUntil($endDate);


        $this->setSelectedPeriod($response);

        $responses->addResponse($this->getKey(), $response);

        return $response;
    }

    public function getReportData(): ?string
    {
        return sprintf('Selected time period: %s', $this->selectedPeriod);
    }
}
