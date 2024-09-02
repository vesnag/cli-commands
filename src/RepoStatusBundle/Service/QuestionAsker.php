<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Collection\ResponseCollection;
use App\RepoStatusBundle\Question\QuestionInterface;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @template T
 */
class QuestionAsker
{
    /**
     * @param QuestionInterface<T>[] $questions
     * @param HelperInterface $helper
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return ResponseCollection<T>
     */
    public function askQuestions(array $questions, HelperInterface $helper, InputInterface $input, OutputInterface $output): ResponseCollection
    {
        if (!$helper instanceof QuestionHelper) {
            throw new \RuntimeException('The "question" helper is not available.');
        }

        /** @var ResponseCollection<T> $responses */
        $responses = new ResponseCollection();

        foreach ($questions as $question) {
            $response = $helper->ask($input, $output, $question->createQuestion());
            $question->handleResponse($response, $responses, $input, $output);
        }

        return $responses;
    }
}
