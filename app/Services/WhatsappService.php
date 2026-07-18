<?php

namespace App\Services;

use App\Models\WhatsappTemplate;
use Twilio\Rest\Client;

class WhatsappService
{
    protected Client $client;
    protected string $from;

    public function __construct()
    {
        $this->client = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $this->from = 'whatsapp:' . config('services.twilio.whatsapp_from');
    }

    public function send(string $to, string $message): bool
    {
        try {
            $this->client->messages->create(
                'whatsapp:' . $to,
                [
                    'from' => $this->from,
                    'body' => $message,
                ]
            );

            return true;

        } catch (\Exception $e) {
            \Log::error('WhatsApp send error: ' . $e->getMessage());
            return false;
        }

    }// end of send

    public function sendByType(string $type, string $to, array $variables = []): bool
    {
        $template = WhatsappTemplate::where('type', $type)->where('is_active', true)->first();

        if (!$template) {
            return false;
        }

        $message = $template->buildMessage($variables);

        return $this->send($to, $message);

    }// end of sendByType

}// end of service
