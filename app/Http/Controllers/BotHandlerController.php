<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;

class BotHandlerController extends Controller
{
    public function telegramHandler(){
        $updates = Telegram::getWebhookUpdates();

        $chat_id = $updates->getMessage()->getChat()->getId();
        if($updates->getMessage()->getText() == '/start'){
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Selamat datang!'
            ]);
        }else{
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Perintah tidak ditemukan!'
            ]);
        }
    }
}
