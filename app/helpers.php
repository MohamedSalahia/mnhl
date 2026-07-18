<?php

use App\Services\WhatsappService;

if (!function_exists('send_whatsapp')) {
    function send_whatsapp(string $type, string $to, array $variables = []): bool
    {
        return app(WhatsappService::class)->sendByType($type, $to, $variables);
    }
}
