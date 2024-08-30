<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\Exception\InvalidStringValueException;
use App\RepoStatusBundle\Command\CheckRepositoryStatusCommand;
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
        $responseKeys = [
            'timePeriodResponse' => CheckRepositoryStatusCommand::TIME_PERIOD,
            'getPrsResponse' => CheckRepositoryStatusCommand::GET_COUNT_PRS,
            'getCommitsResponse' => CheckRepositoryStatusCommand::GET_COUNT_COMMITS,
            'generateSlackReport' => CheckRepositoryStatusCommand::GENERATE_SLACK_REPORT,
            'publishToSlackResponse' => CheckRepositoryStatusCommand::PUBLISH_TO_SLACK,
        ];

        $responses = $this->convertResponses($responses, $responseKeys, $output);
        if ($responses === null) {
            return;
        }

        [$timePeriodResponse, $getPrsResponse, $getCommitsResponse, $generateSlackReport, $publishToSlackResponse] = $responses;

        [$startDate, $endDate] = $this->determineDateRange($timePeriodResponse);

        $params = new ResponseParams(
            $startDate,
            $endDate,
            $timePeriodResponse,
            $getPrsResponse,
            $getCommitsResponse,
            $generateSlackReport,
            $publishToSlackResponse
        );

        $this->responseProcessor->processResponses($params, $output);
    }

    /**
     * @param array<string, mixed> $responses
     * @param array<string, string> $responseKeys
     * @return array<string>
     */
    private function convertResponses(array $responses, array $responseKeys, OutputInterface $output): ?array
    {
        $convertedResponses = [];

        try {
            foreach ($responseKeys as $variable => $constant) {
                $convertedResponses[] = isset($responses[$constant]) ? ConvertTo::string($responses[$constant], $constant) : '';
            }
        } catch (InvalidStringValueException $e) {
            $output->writeln($e->getMessage());
            return null;
        }

        return $convertedResponses;
    }

    /**
     * @param string $timePeriodResponse
     * @return array{string|null, string|null}
     */
    private function determineDateRange(string $timePeriodResponse): array
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
