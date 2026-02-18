<?php

namespace App\Services;

use App\Models\SettingsModel;
use Config\Services;

class WebhookService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = new SettingsModel();
    }

    public function notifySuccess($queueItem)
    {
        $url = $this->settings->getSetting('webhook_success_url');
        if (!$url) return;

        $this->callWebhook($url, [
            'event'   => 'email_sent',
            'data'    => [
                'id'       => $queueItem->id,
                'to'       => $queueItem->to_email,
                'sent_at'  => $queueItem->sent_at,
                'subject'  => $queueItem->subject,
            ]
        ]);
    }

    public function notifyFailed($queueItem)
    {
        $url = $this->settings->getSetting('webhook_failed_url');
        if (!$url) return;

        $this->callWebhook($url, [
            'event'   => 'email_failed',
            'data'    => [
                'id'            => $queueItem->id,
                'to'            => $queueItem->to_email,
                'error'         => $queueItem->error_message,
                'attempts'      => $queueItem->attempts,
                'max_attempts'  => $queueItem->max_attempts,
            ]
        ]);
    }

    protected function callWebhook($url, $payload)
    {
        $client = Services::curlrequest();
        try {
            $client->post($url, [
                'json'    => $payload,
                'timeout' => 5,
                'headers' => ['Content-Type' => 'application/json']
            ]);
        } catch (\Exception $e) {
            log_message('warning', 'EQS: Webhook failed to ' . $url . ' - ' . $e->getMessage());
        }
    }
}
