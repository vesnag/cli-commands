<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'app.question', priority: -100)]
final class PublishToSlackQuestion implements QuestionInterface
{
    public function __construct()
    {
    }

    public function getKey(): string
    {
        return 'publish_to_slack';
    }

    public function createQuestion(): ConfirmationQuestion
    {
        return new ConfirmationQuestion('Do you want to publish this status to Slack? (yes/no) [no]', false);
    }

    public function handleResponse(mixed $response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): mixed
    {
        $responses->addResponse($this->getKey(), $response);
        return $response;
    }

    public function getReportData(): ?string
    {
        return null;
    }
}
