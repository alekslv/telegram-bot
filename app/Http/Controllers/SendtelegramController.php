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

        $users = User::all();
        $i = 0;
        foreach ($users as $user) {
            $user_regions = DB::table('region_user')
                ->where('user_id', $user->id)
                ->get();
            $user_categories = DB::table('category_user')
                ->where('user_id', $user->id)
                ->get();
            $sql = Item::orderBy('id', 'ASC')->active();// раскоментировать
            //$sql = Item::orderBy('id', 'ASC');// раскоментировать
            $items = $sql->get();
            //if ($user->id == 31) {// удалить!!!!!!!
                foreach ($items as $item) {

                    $res_place = $user_regions->pluck('region')->contains($item->place);
                    $res_category = $user_categories->pluck('category')->contains($item->category);

                    if ($res_place && $res_category) {
                        $data = [
                            'text' => "<strong>Лот:</strong> <a href='https://setam.net.ua/auctions/filters/number=".$item->number."'>" . $item->number."</a>" . chr(10) .
                                      "<strong>Назва:</strong> " . $item->name . chr(10) .
                                      "<strong>Стартова ціна:</strong> " . $item->start_price. chr(10).
                                      "<strong><a href='https://setam.net.ua/auctions/filters/number=".$item->number."'>Подивитись</a></strong>",
                            //'link' => 'https://setam.net.ua/auctions/filters/number=' . $item->number,
                            'parse_mode' => 'html',
                            'chat_id' => $user->telegram_user_id,
                        ];
                        try {
                            $response = Telegram::sendMessage(
                                $data
                            );
                        } catch (\Telegram\Bot\Exceptions\TelegramResponseException $e) {
                            $errorData = $e->getResponseData();
                        }
                        DB::table('items')
                            ->where('id', $item->id)
                            ->update(['status' => 2]);
                        $i++;
                        //if ($i > 2) { // удалить !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                          //  break;   // удалить !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        //}            // удалить !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

                    }
                }
            //}// удалить !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }
    }
}
