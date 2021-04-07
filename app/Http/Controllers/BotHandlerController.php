<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class BotHandlerController extends Controller
{

    public function telegramHandler2(){
        echo json_encode(Telegram::getUpdates());

    }
    public function resetPending(){
        $updates = Telegram::getWebhookUpdates();
        return response('Oke', 200)->header('Content-Type', 'text/plain');
    }
    public function telegramHandler(){
        $token = config('telegram.bots.mybot.token');
        $teleurl = "https://api.telegram.org/file/bot".$token."/";
        $updates = Telegram::getWebhookUpdates();

        $photo = false;
        $location = false;
        if($updates->isType('callback_query')){
            $chat_id = $updates->callbackQuery->from->id;
            $message = $updates->callbackQuery->data;
        }elseif($updates->getMessage()->has('photo')){
            $chat_id = $updates->getMessage()->getChat()->getId();
            $photo = $updates->getMessage()->getPhoto();
            $message =  $photo[2]['file_id'];
            $message = Telegram::getFile(['file_id' => $message])->file_path;
            $photo = true;
        }elseif($updates->getMessage()->has('location')){
            $chat_id = $updates->getMessage()->getChat()->getId();
            $message = $updates->getMessage()->getLocation();
            $location = true;
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
                            }elseif($pel->upgraded == 1 and $pel->confirmed == 0){
                                $add = 'KWH meter Anda sudah diperbarui dan sedang menunggu konfirmasi dari petugas. Silahkan cek beberapa saat lagi.';
                                $keyboard = [
                                    [
                                        Keyboard::inlineButton(['text' => 'Reset', 'callback_data' => '/reset'])
                                    ]
                                ];
                            }elseif($pel->upgraded == 1 and $pel->confirmed == 0){
                                $add = 'KWH meter Anda sudah diperbarui dan sudah dikonfirmasi oleh petugas. Terima kasih telah melakukan pembaruan perangkat lunak KWH meter.';
                                $keyboard = [
                                    [
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
                    }else{
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'Perintah tidak ditemukan!',
                        ]);
                    }
                }elseif($session->session_name == 'Show Data'){
                    if($message == 'Update'){
                        $session->session_name = 'Update';
                        $session->save();
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        $keyboard = [
                            [
                                Keyboard::inlineButton(['text' => 'Benar', 'callback_data' => 'Benar']),
                                Keyboard::inlineButton(['text' => 'Salah', 'callback_data' => 'Salah'])
                            ],
                            [
                                Keyboard::inlineButton(['text' => 'Reset', 'callback_data' => '/reset'])
                            ]
                        ];
                        $reply_markup = $this->replyMarkup($keyboard);
                        $chat = 'Masukkan dengan cara menekan token berikut secara berurutan. Lakukan seperti input token pulsa. Tekan nomor token kemudian tekan Enter (Tanda Panah).

<b>KCT1: '.$pel->kct1a.'</b> (Enter)

<b>KCT2: '.$pel->kct1b.'</b> (Enter)

Pastikan semua token update sudah dimasukkan dengan benar, lalu perhatikan layar KWH meter Anda.
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
                    if(in_array($message, ['Benar', 'Salah'])){
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        if($message == 'Benar'){
                            $pel->kct1 = true;
                            $pel->save();
                        }elseif($message == 'Salah'){
                            $pel->kct1 = false;
                            $pel->save();
                        }
                        if($pel->vkrn == 41){
                            $session->session_name = 'Done Update';
                            $session->save();
                            $chat = 'Tekan angka 04 pada KWH meter Anda, lalu foto layar KWH meter Anda dan kirim ke chat ini.';
                            $response = Telegram::sendMessage([
                                'chat_id' => $chat_id,
                                'text' => $chat,
                                'reply_markup' => $this->resetButton()
                            ]);
                        }elseif($pel->vkrn == 42){
                            $session->session_name = 'Update 2';
                            $session->save();
                            $keyboard = [
                                [
                                    Keyboard::inlineButton(['text' => 'Benar', 'callback_data' => 'Benar']),
                                    Keyboard::inlineButton(['text' => 'Salah', 'callback_data' => 'Salah'])
                                ],
                                [
                                    Keyboard::inlineButton(['text' => 'Reset', 'callback_data' => '/reset'])
                                ]
                            ];
                            $reply_markup = $this->replyMarkup($keyboard);
                            $chat = 'Masukkan dengan cara menekan token berikut secara berurutan. Lakukan seperti input token pulsa. Tekan nomor token kemudian tekan Enter (Tanda Panah).

<b>KCT3: '.$pel->kct2a.'</b> (Enter)

<b>KCT4: '.$pel->kct2b.'</b> (Enter)

Pastikan semua token update sudah dimasukkan dengan benar, lalu perhatikan layar KWH meter Anda.
Tekan tombol dibawah sesuai dengan pesan yang ada di layar KWH meter.';
                            $response = Telegram::sendMessage([
                                'chat_id' => $chat_id,
                                'text' => $chat,
                                'parse_mode' => 'html',
                                'reply_markup' => $reply_markup
                            ]);
                        }
                    }elseif($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }else{
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'Perintah tidak ditemukan!',
                        ]);
                    }
                }elseif($session->session_name == 'Update 2'){
                    if(in_array($message, ['Benar', 'Salah'])){
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        if($message == 'Benar'){
                            $pel->kct2 = true;
                            $pel->save();
                        }elseif($message == 'Salah'){
                            $pel->kct2 = false;
                            $pel->save();
                        }
                        $session->session_name = 'Done Update';
                        $session->save();
                        $chat = 'Tekan angka 04 pada KWH meter Anda, lalu foto layar KWH meter Anda dan kirim ke chat ini dan tekan tombol selesai.';
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat,
                            'reply_markup' => $this->resetButton()
                        ]);
                    }elseif($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }else{
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'Perintah tidak ditemukan!',
                        ]);
                    }
                }elseif($session->session_name == 'Done Update'){
                    if($message != '/reset' and $photo){
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        $client = new Client([
                            'base_uri' => $teleurl,
                            'timeout'  => 120.0,
                        ]);
                        $res = $client->request('GET', $message);
                        $s3name = 'image/upgrade/'.$pel->idpel.'.png';
                        Storage::disk('s3')->put($s3name, $res->getBody());
                        $filename = Storage::disk('s3')->url($s3name);
                        $pel->img = $filename;
                        $pel->upgraded = true;
                        $pel->upgraded_at = date('Y-m-d H:i:s');
                        $pel->krn = 3;
                        $pel->vkrn = 43;
                        $pel->save();
                        $session->session_name = 'Upload Photo';
                        $session->save();
                        $btn = Keyboard::button([
                            'text' => 'Selesai',
                            'request_location' => true
                        ]);
                        $keyboard = Keyboard::make([
                            'keyboard' => [[$btn]],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true
                        ]);
                        $chat = 'Foto sudah kami terima. Silahkan tekan tombol selesai dibawah untuk mengakhiri sesi.';
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat,
                            'reply_markup' => $keyboard
                        ]);
                    }elseif($message != '/reset' and !$photo){
                        $chat = 'Silahkan kirim foto tampilan layar KWH meter anda.';
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat
                        ]);
                    }elseif($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }else{
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'Perintah tidak ditemukan!',
                        ]);
                    }
                }elseif($session->session_name == "Upload Photo"){
                    if($location){
                        $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
                        $pel->lat = $message->latitude;
                        $pel->long = $message->longitude;
                        $pel->save();
                        $chat = 'Terima kasih telah melakukan update software KWH meter. Untuk informasi lebih lanjut silahkan hubungi kantor PLN terdekat.';
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat
                        ]);
                        $session->delete();
                    }elseif(!$location){
                        $chat = 'Silahkan tekan tombol selesai untuk mengakhiri sesi.';
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => $chat
                        ]);
                    }else if($message == '/reset'){
                        $this->resetSession($chat_id, $session);
                    }else{
                        $response = Telegram::sendMessage([
                            'chat_id' => $chat_id,
                            'text' => 'Perintah tidak ditemukan!',
                        ]);
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
                'text' => 'Halo Pelanggan Setia PLN, yuk Update Software Kwh Meter secara Mandiri.

Sebelum memulai pastikan pembelian token pulsa sudah dimasukkan ke kwh meter.

Jangan lupa aktifkan layanan lokasi Smartphone Anda.

Silahkan masukkan nomor meter anda untuk memulai.',
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
        if(!empty($session->last_message)){
            $pel = \App\Models\Pelanggan::where('no_meter', $session->last_message)->first();
            $pel->krn = $pel->krn_lama;
            $pel->vkrn = $pel->vkrn_lama;
            $pel->kct1 = false;
            $pel->kct2 = false;
            $pel->upgraded = 0;
            $pel->upgraded_at = null;
            $pel->confirmed = 0;
            $pel->confirmed_at = null;
            $pel->confirmed_by = null;
            $pel->lat = null;
            $pel->long = null;
            $pel->save();
        }
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
