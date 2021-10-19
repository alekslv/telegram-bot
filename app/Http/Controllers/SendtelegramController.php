<?php

namespace App\Http\Controllers;

use App\Notifications\TelegrammBot;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class SendtelegramController extends Controller
{
    public function index()
    {

        $limit = 40;
        $i = 0;

        $users = User::all();
        foreach ($users as $user) {


            $count = DB::table('item_user')
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->count();

            $items_ = DB::table('item_user')
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->take($limit)
                ->get();

            foreach ($items_ as $item_) {
                $item = Item::find($item_->item_id);
                $data = [
                    'text' => "<strong>Лот:</strong> <a href='https://setam.net.ua/auctions/filters/number=" . $item->number . "'>" . $item->number . "</a>" . chr(10) .
                        "<strong>Назва:</strong> " . $item->name . chr(10) .
                        "<strong>Стартова ціна:</strong> " . $item->start_price . chr(10) .
                        "<strong><a href='https://setam.net.ua/auctions/filters/number=" . $item->number . "'>Подивитись</a></strong>",
                    'parse_mode' => 'html',
                    'chat_id' => $user->telegram_user_id,
                ];
                $response = Telegram::sendMessage(
                    $data
                );
                // обновить статус
                DB::table('item_user')
                    ->where('user_id', $user->id)
                    ->where('item_id', $item_->item_id)
                    ->update(['status' => 2]);
                $i++;
            }
        }



        /*
        $i = 0;
        $arr_updates = [];
        $sql = Item::orderBy('id', 'ASC')->active(); // раскоментировать
        $items = $sql->limit(50)->get();

        foreach ($users as $user) {
            $user_regions = DB::table('region_user')
                ->where('user_id', $user->id)
                ->get();
            $user_categories = DB::table('category_user')
                ->where('user_id', $user->id)
                ->get();
            foreach ($items as $item) {
                $res_place = $user_regions->pluck('region')->contains($item->place);
                $res_category = $user_categories->pluck('category')->contains($item->category);
                if ($res_place && $res_category) {
                    $data = [
                        'text' => "<strong>Лот:</strong> <a href='https://setam.net.ua/auctions/filters/number=" . $item->number . "'>" . $item->number . "</a>" . chr(10) .
                            "<strong>Назва:</strong> " . $item->name . chr(10) .
                            "<strong>Стартова ціна:</strong> " . $item->start_price . chr(10) .
                            "<strong><a href='https://setam.net.ua/auctions/filters/number=" . $item->number . "'>Подивитись</a></strong>",
                        'parse_mode' => 'html',
                        'chat_id' => $user->telegram_user_id,
                    ];
                    $response = Telegram::sendMessage(
                        $data
                    );
                    $arr_updates[] = $item->id;
                    $i++;
                }
            }
        }
        Log::channel('send')->info('Отправлено сообщений - ' . $i);
        //ставим статус
        //не в работе
        if (!empty($arr_updates)) {
            foreach ($arr_updates as $arr_update) {
                DB::table('items')
                    ->where('id', $arr_update)
                    ->update(['status' => 2]);
            }
        }
        */

    }
}
