<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\Exception\InvalidStringValueException;
use App\RepoStatusBundle\DTO\ResponseParams;
use App\Utils\ConvertTo;
use Symfony\Component\Console\Output\OutputInterface;

class QuestionAnswerHandler
{
    public function __construct(
        private ResponseProcessor $responseProcessor
    ) {
    }

    /**
     * @param array<string, mixed> $responses
     */
    public function handleResponses(array $responses, OutputInterface $output): void
    {
        try {
            $timePeriodResponse = ConvertTo::string($responses['time'] ?? null, 'time');
        } catch (InvalidStringValueException $e) {
            $output->writeln('<error>Invalid time period response: ' . $e->getMessage() . '</error>');
            return;
        }

        [$startDate, $endDate] = $this->determineDateRange($timePeriodResponse);

        $params = new ResponseParams(
            $startDate,
            $endDate,
            $timePeriodResponse,
            ConvertTo::string($responses['get_count_prs'] ?? null, 'get_count_prs'),
            ConvertTo::string($responses['get_count_commits'] ?? null, 'get_count_commits'),
            ConvertTo::string($responses['generate_slack_report'] ?? null, 'generate_slack_report'),
            ConvertTo::string($responses['publish_to_slack'] ?? null, 'publish_to_slack')
        );

        $this->responseProcessor->processResponses($params, $output);
    }

    /**
     * @param string|null $timePeriodResponse
     * @return array{string|null, string|null}
     */
    private function determineDateRange(?string $timePeriodResponse): array
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
