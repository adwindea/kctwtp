<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;

class BotHandlerController extends Controller
{
    public function telegramHandler(){
        // dd(Telegram::getUpdates());
        $updates = Telegram::getWebhookUpdates();
        if($updates->getText == '/start'){
            $chat_id = $updates['chat']['id'];
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Selamat Datang!'
            ]);
        }
    }
}
