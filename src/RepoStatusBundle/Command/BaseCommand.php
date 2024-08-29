<?php

namespace App\RepoStatusBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;

abstract class BaseCommand extends Command
{
  /**
   * @return array<string, mixed>
   */
    protected function askQuestions(InputInterface $input, OutputInterface $output): array
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $helper */
        $helper = $this->getHelper('question');
        $questions = $this->getQuestions();

        $responses = [];
        foreach ($questions as $key => $question) {
            $response = $helper->ask($input, $output, $question);
            $responses[$key] = $response;
        }

        return $responses;
    }

     /**
     * @return array<string, ConfirmationQuestion|ChoiceQuestion>
     */
    abstract protected function getQuestions(): array;
}
