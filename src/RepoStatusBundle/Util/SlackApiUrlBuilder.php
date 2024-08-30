<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Util;

class SlackApiUrlBuilder
{
    public function __construct(
        private string $slackApiBaseUrl,
    ) {
    }

    public function constructApiUrl(string $endpoint): string
    {
        return sprintf(
            '%s/%s',
            $this->slackApiBaseUrl,
            $endpoint
        );
    }
}
