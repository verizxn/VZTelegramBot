<?php

require_once __DIR__.'/../vendor/autoload.php';

$telegram = new \verizxn\VZTelegramBot\Telegram('BOT_TOKEN', __DIR__.'/logs');

while(true){
    $update = $telegram->getUpdate();
    if(!$update) continue;
    
    if(isset($update['message']['text'])){
        if (stripos($update['message']['text'], '/start') === 0) {
            $request = explode(' ', $update['message']['text'], 2);
            $text = "Hello <b>{$update['message']['from']['first_name']}</b>!";

            if(isset($request[1])) $text .= "\n\n$request[1]";

            $telegram->request('sendMessage', [
                'chat_id' => $update['message']['chat']['id'],
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => $telegram->generateInlineKeyboard([[['text' => 'Button', 'url' => 'https://www.google.com']]])
            ]);
        }
    }
}