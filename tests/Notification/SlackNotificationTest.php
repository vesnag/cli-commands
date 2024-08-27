<?php

declare(strict_types=1);

namespace App\Tests\Notification;

use App\Notification\SlackNotification;
use PHPUnit\Framework\TestCase;

class SlackNotificationTest extends TestCase
{
    public function testSend()
    {
        $config = [
            'webhook_url' => 'https://hooks.slack.com/services/dummy_webhook'
        ];
        $notification = new SlackNotification($config);

        // Mock the API call and response
        $result = $notification->send('Test message');

        $this->assertTrue($result);
    }
}
