<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Question;

use Symfony\Component\Console\Question\Question;

interface QuestionInterface
{
    public function getKey(): string;
    public function createQuestion(): Question;
}
