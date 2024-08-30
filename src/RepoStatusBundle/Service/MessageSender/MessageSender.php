<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service\MessageSender;

interface MessageSender
{
    /**
    * @return array<string, array<string, string>>
    */
    public function sendMessage(string $message): array;
}
