<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;

class BotHandlerController extends Controller
{
    public function telegramHandler(){
        $updates = Telegram::getWebhookUpdates();

        // $chat_id = $updates->getMessage()->getChat()->getId();

        // $response = Telegram::sendMessage([
        //   'chat_id' => $chat_id,
        //   'text' => 'Hello World'
        // ]);

        // $messageId = $response->getMessageId();

        // dd(Telegram::getUpdates());
        // $updates = Telegram::getWebhookUpdates();
        if($updates->getText == '/start'){
            $chat_id = $updates['chat']['id'];
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Selamat Datang!'
            ]);
        }else{
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Perintah tidak ditemukan!'
            ]);
        }
    }
}
