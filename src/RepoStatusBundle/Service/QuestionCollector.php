<?php

namespace App\RepoStatusBundle\Service;

class QuestionCollector
{
    private $questions;

    public function __construct(iterable $questions)
    {
        $this->questions = $questions;
    }

    public function getQuestions(): iterable
    {
        return $this->questions;
    }
}
