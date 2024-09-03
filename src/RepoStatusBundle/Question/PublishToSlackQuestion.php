<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use App\RepoStatusBundle\Collection\ResponseCollection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

/**
 * @implements QuestionInterface<bool>
 */
#[AsTaggedItem(index: 'app.question', priority: -100)]
class PublishToSlackQuestion implements QuestionInterface
{
    private bool $publishToSlack;

    public function getKey(): string
    {
        return 'publish_to_slack';
    }

    public function createQuestion(): ConfirmationQuestion
    {
        return new ConfirmationQuestion(
            'Do you want to publish the report to Slack? (yes/no) [false]',
            false
        );
    }

    /**
     * Handle the response for the publish to Slack question.
     *
     * @param bool $response
     * @param ResponseCollection<bool> $responses
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function handleResponse($response, ResponseCollection $responses, InputInterface $input, OutputInterface $output): void
    {
        $this->publishToSlack = $response;
        $responses->addResponse($this->getKey(), $response, $this);
    }

    public function shouldPublishToSlack(): bool
    {
        return $this->publishToSlack;
    }

    public function getReportData(): ?string
    {
        return null;
    }
}
