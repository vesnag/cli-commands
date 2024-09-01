<?php

declare(strict_types=1);

namespace App\RepoStatusBundle\Service\MessageSender;

use App\RepoStatusBundle\Config\SlackConfig;
use App\RepoStatusBundle\Util\SlackApiUrlBuilder;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SlackMessageSender implements MessageSender
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SlackApiUrlBuilder $urlBuilder,
        private SlackConfig $config
    ) {
    }

    public function sendMessage(string $message): bool
    {
        $apiUrl = $this->urlBuilder->constructApiUrl('chat.postMessage');

        $response = $this->httpClient->request('POST', $apiUrl, [
            'headers' => [
                'Authorization' => "Bearer {$this->config->getToken()}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'channel' => sprintf('#%s', $this->config->getChannel()),
                'text' => $message,
            ],
        ]);

        return $response->getStatusCode() === 200;
    }
}
