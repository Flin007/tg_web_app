<?php

namespace App\Helpers;

use Telegram\Bot\BotsManager;
use Telegram\Bot\Objects\Update;

final class TelegramBotNotificationHelper
{
    public static function sendLog(string $title, array|Update $webhook = ['Без хука)']): void
    {
        $botManager = app(BotsManager::class);
        $bot = $botManager->bot();
        $message = $title;
        $message .= PHP_EOL . json_encode($webhook, JSON_UNESCAPED_UNICODE);
        $bot->sendMessage([
            'chat_id' => env('ERRORS_CHAT_ID'),
            'text' => $message,
        ]);
    }
}
