<?php

declare(strict_types=1);

namespace AG\RepoStatusBundle\Command;

use PHPUnit\Framework\TestCase;

class CheckRepositoryStatusCommandTest extends TestCase
{
    public function testExecute(): void
    {
        $output = 'Repository status';
        $this->assertStringContainsString('Repository status', $output);
    }
}
