<?php

declare(strict_types=1);

/**
 * @file
 * This file contains tasks for static analysis, code formatting, and unit testing using Castor.
 *
 * Castor is a task runner for PHP applications. It allows you to define and run tasks easily.
 *
 * To run the tasks defined in this file, use the following commands:
 *
 * Run static analysis and code formatting:
 * $ castor code:validate
 *
 * Run unit tests:
 * $ castor code:run-tests
 *
 * Run both validation and unit tests:
 * $ castor code:validate-and-test
 */

namespace code;

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\run;
use function Castor\notify;

#[AsTask(description: 'Run static analysis and code formatting')]
function validate(bool $force = false): void
{
    io()->writeln('Starting static analysis and code formatting...');

    try {
        if (!executeCommand('vendor/bin/phpstan analyse', 'phpstan has been successfully run.', 'There were issues running phpstan.')) {
            return;
        }

        if (!executeCommand('vendor/bin/phpcbf --standard=PSR12 src/', 'phpcbf has been successfully run.', 'There were issues running phpcbf.')) {
            return;
        }

        notify('Static analysis and code formatting completed successfully.');
    } catch (\Exception $e) {
        io()->error('An error occurred: ' . $e->getMessage());
        notify('An error occurred during the validation process.');
    }
}

#[AsTask(description: 'Run unit tests')]
function runTests(): void
{
    io()->writeln('Starting unit tests...');

    try {
        if (!executeCommand('vendor/bin/phpunit', 'phpunit has been successfully run.', 'There were issues running phpunit.')) {
            return;
        }

        notify('Unit tests completed successfully.');
    } catch (\Exception $e) {
        io()->error('An error occurred: ' . $e->getMessage());
        notify('An error occurred during the unit tests.');
    }
}

#[AsTask(description: 'Run validation and unit tests')]
function validateAndTest(): void
{
    io()->writeln('Starting validation and unit tests...');

    try {
        validate();
        runTests();
        notify('Validation and unit tests completed successfully.');
    } catch (\Exception $e) {
        io()->error('An error occurred: ' . $e->getMessage());
        notify('An error occurred during the validation and unit tests process.');
    }
}

/**
 * Helper function to run a command and handle errors.
 */
function executeCommand(string $command, string $successMessage, string $errorMessage): bool
{
    $result = run($command);
    if (!$result->isSuccessful()) {
        io()->error($errorMessage);
        notify($errorMessage);
        return false;
    }
    io()->success($successMessage);
    return true;
}
