<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;

class BotHandlerController extends Controller
{
    public function telegramHandler(){
        $updates = Telegram::getWebhookUpdates();

        $chat_id = $updates->getMessage()->getChat()->getId();
        $message = $updates->getMessage()->getText();
        // $response = Telegram::sendMessage([
        //     'chat_id' => $chat_id,
        //     'text' => $message
        // ]);
        $session = \App\Models\TelegramSession::where('chat_id', $chat_id)->first();
        if(!empty($session)){
            if(!empty($session->session_name)){
                $this->processSession($session, $chat_id, $message);
            }else{
                $this->startSession($chat_id, $message);
            }
        }else{
            $this->startSession($chat_id, $message);
        }
    }

    function unsetWebhook(){
        Telegram::deleteWebhook();
    }

    function startSession($chat_id, $message){
        $message = strtolower($message);
        if($message == '/start'){
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Halo, silahkan masukkan nomor meter anda untuk memulai.'
            ]);
            $session = new \App\Models\TelegramSession;
            $session->chat_id = $chat_id;
            $session->session_name = 'Start';
            $session->save();
        }else{
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Perintah tidak ditemukan! Mulai dengan perintah "/start"'
            ]);
        }
    }

    function processSession($session, $chat_id, $message){
        if($session->session_name == 'Start'){
            $pel = \App\Models\Pelanggan::where('no_meter', $message)->first();
            if(!empty($pel)){
                $add = '';
                $reply_markup = '';
                if($pel->upgraded == 0 and ($pel->vkrn == 41 or $pel->vkrn == 42)){
                    $add = 'KWH meter Anda saat ini versi KRN'.$pel->vkrn.'. Diperlukan update ke versi KRN43. Silahkan tekan tombol "Update" untuk mendapatkan token untuk update software';
                    $keyboard = ['Update'];
                    $reply_markup = Telegram::replyKeyboardMarkup([
                        'keyboard' => $keyboard,
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true
                    ]);
                }else{
                    $add = 'KWH meter Anda saat ini versi KRN43 dan tidak diperlukan update.';
                    $session->session_name = 'Start';
                    $session->save();
                }
                $chat = 'Informasi Pelanggan
IDPEL : '.$pel->idpel.'
Nama : '.$pel->nama.'
Tarif : '.$pel->tarif.'
Daya : '.number_format($pel->daya, 0, '', '').'
Alamat : '.$pel->alamat.'
Versi KWH : KRN'.$pel->vkrn.'
'.$add;
                $response = Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => $chat,
                    'reply_markup' => $reply_markup
                ]);
            }
        }elseif($session->session_name == 'Input Number'){

        }elseif($message == '/reset'){
            $session->delete();
        }
    }
}
