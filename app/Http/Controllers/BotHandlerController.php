<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use Telegram\Bot\Keyboard\Keyboard;

class BotHandlerController extends Controller
{
    public function telegramHandler2(){
        echo json_encode(Telegram::getUpdates());

    }
    public function telegramHandler(){
        $updates = Telegram::getWebhookUpdates();

        if($updates->isType('callback_query')){
            $chat_id = $updates->callbackQuery->from->id;
            $message = $updates->callbackQuery->data;
        }else{
            $chat_id = $updates->getMessage()->getChat()->getId();
            $message = $updates->getMessage()->getText();
        }
        // $response = Telegram::sendMessage([
        //     'chat_id' => $chat_id,
        //     'text' => $message
        // ]);
        $session = \App\Models\TelegramSession::where('chat_id', $chat_id)->first();
        if(!empty($session)){
            if(!empty($session->session_name)){
                if($session->session_name == 'Start'){
                    if($message != '/reset'){
                        $pel = \App\Models\Pelanggan::where('no_meter', $message)->first();
                        if(!empty($pel)){
                            $add = '';
                            if($pel->upgraded == 0 and ($pel->vkrn == 41 or $pel->vkrn == 42)){
                                $session->session_name = 'Show Data';
                                $session->last_message = $message;
                                $session->save();
                                $add = 'KWH meter Anda saat ini versi KRN'.$pel->krn.'. Diperlukan update ke versi KRN43. Silahkan tekan tombol "Update" untuk mendapatkan token untuk update software';
                                $keyboard = [
                                    [
                                        Keyboard::inlineButton(['text' => 'Update', 'callback_data' => 'Update']),
                                        Keyboard::inlineButton(['text' => 'Reset', 'callback_data' => '/reset'])
                                    ]
                                ];
                            }else{
                                $session->session_name = 'Start';
                                $session->save();
                                $add = 'KWH meter Anda saat ini versi KRN43 dan tidak diperlukan update. Silahkan masukkan nomor meter lainnya untuk melanjutkan.';
                                $keyboard = [
                                    [
                                        Keyboard::inlineButton(['text' => 'Reset', 'callback_data' => '/reset'])
                                    ]
                                ];
                            }
                            $reply_markup = $this->replyMarkup($keyboard);
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
                        }else{
                            $session->session_name = 'Start';
                            $session->save();
                            $chat = 'Data yang Anda masukkan tidak ditemukan atau KWH Anda tidak memerlukan update. Silahkan masukkan nomor meter lainnya untuk melanjutkan.';
                            $response = Telegram::sendMessage([
                                'chat_id' => $chat_id,
                                'text' => $chat,
                                'reply_markup' => $this->resetButton()
                            ]);
                        }
                    }elseif($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }
                }elseif($session->session_name == 'Show Data'){
                    if($message == 'Update'){
                        $session->session_name = 'Update';
                        $session->save();
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        $keyboard = [
                            [
                                Keyboard::inlineButton(['text' => 'Benar', 'callback_data' => 'Benar']),
                                Keyboard::inlineButton(['text' => 'Salah', 'callback_data' => '/reset'])
                            ]
                        ];
                        $reply_markup = $this->replyMarkup($keyboard);
                        $chat = 'Masukkan nomor token berikut secara berurutan dan tekan enter di setiap nomor tokennya.

<b>KCT1: '.$pel->kct1a.'</b>

<b>KCT2: '.$pel->kct1b.'</b>

Pastikan semua token terisi dengan benar, lalu perhatikan layar KWH meter Anda.
Tekan tombol dibawah sesuai dengan pesan yang ada di layar KWH meter.';
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat,
                            'parse_mode' => 'html',
                            'reply_markup' => $reply_markup
                        ]);
                    }elseif($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }else{
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'Perintah tidak ditemukan!'
                        ]);
                    }
                }elseif($session->session_name == 'Update'){
                    if($message == 'Benar'){
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        $pel->kct1 = true;
                        $pel->save();
                        if($pel->vkrn == 41){
                            $session->session_name = 'Done Update';
                            $session->save();
                            $chat = 'Tekan angka 04 pada KWH meter Anda, lalu foto layar KWH meter Anda dan kirim ke chat ini.';
                            $response = Telegram::sendMessage([
                                'chat_id' => $chat_id,
                                'text' => $chat
                            ]);
                        }elseif($pel->vkrn == 42){
                            $session->session_name = 'Update 2';
                            $session->save();
                            $keyboard = [
                                [
                                    Keyboard::inlineButton(['text' => 'Benar', 'callback_data' => 'Benar']),
                                    Keyboard::inlineButton(['text' => 'Salah', 'callback_data' => '/reset'])
                                ]
                            ];
                            $reply_markup = $this->replyMarkup($keyboard);
                            $chat = 'Masukkan lagi nomor token berikut secara berurutan dan tekan enter di setiap nomor tokennya.

<b>KCT3: '.$pel->kct2a.'</b>

<b>KCT4: '.$pel->kct2b.'</b>

Pastikan semua token terisi dengan benar, lalu perhatikan layar KWH meter Anda.
Tekan tombol dibawah sesuai dengan pesan yang ada di layar KWH meter.';
                            $response = Telegram::sendMessage([
                                'chat_id' => $chat_id,
                                'text' => $chat,
                                'parse_mode' => 'html',
                                'reply_markup' => $reply_markup
                            ]);
                        }
                    }
                }elseif($session->session_name == 'Update 2'){
                    if($message == 'Benar'){
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        $pel->kct2 = true;
                        $pel->save();
                        $session->session_name = 'Done Update';
                        $session->save();
                        $chat = 'Tekan angka 04 pada KWH meter Anda, lalu foto layar KWH meter Anda dan kirim ke chat ini.';
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat
                        ]);
                        $btn = Keyboard::button([
                            'text' => 'Selesai',
                            'request_contact' => true,
                            'request_location' => true
                        ]);
                        $keyboard = Keyboard::make([
                            'keyboard' => [[$btn]],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ]);
                    }elseif($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }else{
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'Perintah tidak ditemukan!',
                            'reply_markup' => $keyboard
                        ]);
                    }
                }elseif($session->session_name == 'Done Update'){
                    if($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }
                }
            }else{
                $response = Telegram::sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'Perintah tidak ditemukan! Mulai dengan perintah "/start"',
                    'reply_markup' => $this->startButton()
                ]);
            }
        }else{
            $this->startSession($chat_id, $message);
        }
    }
    function startButton(){
        $keyboard = [
            [
                Keyboard::inlineButton(['text' => 'Start', 'callback_data' => '/start'])
            ]
        ];
        $reply_markup = $this->replyMarkup($keyboard);
        return $reply_markup;
    }
    function resetButton(){
        $keyboard = [
            [
                Keyboard::inlineButton(['text' => 'Reset', 'callback_data' => '/reset'])
            ]
        ];
        $reply_markup = $this->replyMarkup($keyboard);
        return $reply_markup;
    }
    function replyMarkup($keyboard){
        $reply_markup = Keyboard::make([
            'inline_keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
        return $reply_markup;
    }
    function startSession($chat_id, $message){
        $message = strtolower($message);
        if($message == '/start'){

            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Halo, silahkan masukkan nomor meter anda untuk memulai.',
                'reply_markup' => $this->resetButton()
            ]);
            $session = new \App\Models\TelegramSession;
            $session->chat_id = $chat_id;
            $session->session_name = 'Start';
            $session->save();
        }else{
            $response = Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'Perintah tidak ditemukan! Mulai dengan perintah "/start"',
                'reply_markup' => $this->startButton()
            ]);
        }
    }
    function resetSession($chat_id, $session){
        $session->delete();
        $response = Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'Mulai dengan perintah "/start"',
            'reply_markup' => $this->startButton()
        ]);

    }

    function unsetWebhook(){
        Telegram::deleteWebhook();
    }
}
