<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service;

use App\RepoStatusBundle\Question\QuestionInterface;

class QuestionCollector
{
    /**
     * @param QuestionInterface[] $questions
     */
    public function __construct(
        private array $questions
    ) {
    }

    /**
     * @return QuestionInterface[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
}
