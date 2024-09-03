<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
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
    private string $selectedPeriod;

    public function getKey(): string
    {
        return 'time_period';
    }

    public function createQuestion(): Question
    {
        return new ChoiceQuestion(
            'Please select the time period:',
            ['Last 24 hours', 'Last 7 days', 'Last 30 days'],
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
