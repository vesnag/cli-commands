<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service\MessageSender;

interface MessageSender
{
    public function sendMessage(string $message): bool;
}
