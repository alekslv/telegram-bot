<?php


namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use \App\Notifications\TelegrammBot;
use Telegram\Bot\Laravel\Facades\Telegram;

class UserService
{

    static public function add($response){

        $telegram_user_id = $response->message->from->id;
        $chat_user_id = $response->message->chat->id;
        $time = Carbon::now();

        $fio = '';
        if (isset($response->message->from->first_name)) {
            $fio .= $response->message->from->first_name;
        }
        if (isset($response->message->from->last_name)) {
            $fio .= ' ' . $response->message->from->last_name;
        }
        $username = $response->message->from->username;

        DB::table('users')->insert([
            'telegram_user_id' => $telegram_user_id,
            'name' => $username,
            'fio' => $fio,
            'created_at' => $time,
            'updated_at' => $time,
        ]);



    }
}
