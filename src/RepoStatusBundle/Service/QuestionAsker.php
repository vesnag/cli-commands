<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Question\QuestionInterface;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;

class QuestionAsker
{
    /**
     * @param QuestionInterface[] $questions
     * @return array<string, mixed>
     */
    public function askQuestions(array $questions, HelperInterface $helper, InputInterface $input, OutputInterface $output): array
    {
        if (!$helper instanceof QuestionHelper) {
            throw new \RuntimeException('The "question" helper is not available.');
        }

        $responses = [];
        foreach ($questions as $question) {
            $response = $helper->ask($input, $output, $question->createQuestion());
            if ($question->getKey() === 'confirm_repo_check' && false === $response) {
                $output->writeln('<comment>Operation cancelled by user.</comment>');
                exit(Command::SUCCESS);
            }
            $responses[$question->getKey()] = $response;
        }
        return $responses;
    }
}
