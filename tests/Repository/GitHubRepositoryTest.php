<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\GitHubRepository;
use PHPUnit\Framework\TestCase;

class GitHubRepositoryTest extends TestCase
{
    public function testCheckStatus()
    {
        $config = [
            'token' => 'dummy_token',
            'repo' => 'dummy_repo'
        ];
        $repository = new GitHubRepository($config);

        // Mock the API call and response
        $status = $repository->checkStatus();

        $this->assertNotEmpty($status);
    }
}
