<?php


namespace App\Services;
use \App\Notifications\TelegrammBot;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Keyboard\Keyboard;

class CommandService
{

    static public function region(){

        $update = Telegram::commandsHandler(true);
        $telegram_user_id = $update['message']['from']['id'];
        $keyboard = config('telegram_text.region_keyboard');

        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
        // **************************пользователя ********************************
        $region_text = chr(10) . 'Ваші регіони:' . chr(10);
        $user = DB::table('users')
            ->where('telegram_user_id', $telegram_user_id)
            ->first();
        if ($user) {
            $user_id = $user->id;
            $region_users = DB::table('region_user')
                ->where('user_id', $user_id)
                ->get();
            if ($region_users) {
                foreach ($region_users as $region_user) {
                    $region_text .= $region_user->region . chr(10);
                }
            }
            $response = Telegram::sendMessage([
                'text' => 'Додати або видалити регіон' . $region_text,
                'reply_markup' => $reply_markup,
                'chat_id' => $update['message']['chat']['id'],
            ]);

        }

    }

    static public function category(){
        $update = Telegram::commandsHandler(true);
        $telegram_user_id = $update['message']['from']['id'];


        $keyboard = config('telegram_text.category_keyboard');
        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        // **************************пользователя ********************************
        $category_text = chr(10) . 'Ваші категорії:' . chr(10);
        $user = DB::table('users')
            ->where('telegram_user_id', $telegram_user_id)
            ->first();
        if ($user) {
            $user_id = $user->id;
            $category_users = DB::table('category_user')
                ->where('user_id', $user_id)
                ->get();
            if ($category_users) {
                foreach ($category_users as $category_user) {
                    $category_text .= $category_user->category . chr(10);
                }
            }

            $response = Telegram::sendMessage([
                'text' => 'Додати категорію' . $category_text,
                'reply_markup' => $reply_markup,
                'chat_id' => $update['message']['chat']['id'],
            ]);

        }


    }


    static public function back(){

        $update = Telegram::commandsHandler(true);
        $telegram_user_id = $update['message']['from']['id'];

        $keyboard = [
            ['Вибрати регіони'],
            ['Вибрати категорії'],
        ];
        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $text=config('telegram_text.start');
        $response = Telegram::sendMessage([
            'text' => $text,
            'reply_markup' => $reply_markup,
            'chat_id' => $update['message']['chat']['id'],
        ]);

    }

}
