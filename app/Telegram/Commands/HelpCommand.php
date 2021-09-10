<?php


namespace App\Telegram\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{

    protected $name = 'help';

    protected $aliases = ['listcommands2'];

    protected $description = 'Help command, Get a list of all commands';


    public function handle()

    {
        $response = $this->getUpdate();
        $telegram_user_id = $response->getMessage()->from->id;
        $chat_user_id = $response->getMessage()->chat->id;

        $message = $response->message;
        $text = $message->text;

        // старт !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        if ($text == '/start') {

            $user = DB::table('users')
                ->where('telegram_user_id', $telegram_user_id)
                ->first();
            if ($user) {
                $keyboard = [
                    ['Вибрати регіони'],
                    ['Вибрати категорії'],
                ];
                $reply_markup = Keyboard::make([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]);
                $user_id = $user->id;
                $response = $this->replyWithMessage([
                    'text' => 'Start',
                    'reply_markup' => $reply_markup,
                    'chat_id' => $chat_user_id
                ]);
            }
            else {

                $telegram_user_id = $response->getMessage()->from->id;
                $chat_user_id = $response->getMessage()->chat->id;
                $time = Carbon::now();
                $fio = '';
                if (isset($response->getMessage()->from->first_name)) {
                    $fio .= $response->getMessage()->from->first_name;
                }
                if (isset($response->getMessage()->from->last_name)) {
                    $fio .= ' ' . $response->getMessage()->from->last_name;
                }
                $username = $response->getMessage()->from->username;
                DB::table('users')->insert([
                    'telegram_user_id' => $telegram_user_id,
                    'name' => $username,
                    'fio' => $fio,
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);

                $text = config('telegram_text.start');
                $response = $this->replyWithMessage([
                    'text' => $text,
                    'chat_id' => $chat_user_id
                ]);
            }
        }
        // регионы !!!!!!!!!!!!!!!!!!!
        if ($text == '/region') {
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
                $response = $this->replyWithMessage([
                    'text' => 'Додати або видалити регіон' . $region_text,
                    'reply_markup' => $reply_markup,
                    'chat_id' => $chat_user_id
                ]);
            }

        }
        // категории
        if ($text == '/category') {
            $category = config('category');
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
                $response = $this->replyWithMessage([
                    'text' => 'Додати категорію' . $category_text,
                    'reply_markup' => $reply_markup,
                    'chat_id' => $chat_user_id
                ]);
            }
        }
        //******************************************************

    }
}
