<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class QuestionAsker
{
    /**
     * @param array<string, ConfirmationQuestion|ChoiceQuestion> $questions
     * @return array<string, mixed>
     */
    public function askQuestions(array $questions, HelperInterface $helper, InputInterface $input, OutputInterface $output): array
    {
        if (!$helper instanceof QuestionHelper) {
            throw new \RuntimeException('The "question" helper is not available.');
        }

        $responses = [];
        foreach ($questions as $key => $question) {
            $response = $helper->ask($input, $output, $question);
            if ($key === 'confirm_repo_check' && false === $response) {
                $output->writeln('<comment>Operation cancelled by user.</comment>');
                exit(Command::SUCCESS);
            }
            $responses[$key] = $response;
        }
        return $responses;
    }
}
