<?php

require_once __DIR__.'/../vendor/autoload.php';

if(!isset($_GET['api_token'])) die('Missing token.');

$telegram = new \verizxn\VZTelegramBot\Telegram($_GET['api_token'], __DIR__.'/logs');
$update = $telegram->getUpdate();

if(isset($update['text'])){
    if (stripos($update['text'], '/start') === 0) {
        $request = explode(' ', $update['text'], 2);
        $text = "Hello <b>{$update['from']['first_name']}</b>!";

        if(isset($request[1])) $text .= "\n\n$request[1]";

        $telegram->request('sendMessage', [
            'chat_id' => $update['chat']['id'],
            'text' => $text,
            'parse_mode' => 'HTML',
            'reply_markup' => $telegram->generateInlineKeyboard([[['text' => 'Button', 'url' => 'https://www.google.com']]])
        ]);
    }
}