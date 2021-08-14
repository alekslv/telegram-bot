<?php

namespace App\Http\Controllers;

use App\Notifications\TelegrammBot;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendtelegramController extends Controller
{
    public function index()
    {

        $items = Item::active()->residential()->kiev()->get();
        $i = 0;
        if ($items) {
            $users = User::all();
            foreach ($items as $item) {
//                if($i>1){
//                    break;
//                }
                $data = [
                    'text'=>"Лот: ".$item->number."\nНазва: ".$item->name."\nСтартова ціна: ".$item->start_price,
                ];
                foreach ($users as $user) {
                    if ($user->telegram_user_id) {
                        Notification::route('telegram', $user->telegram_user_id)
                            ->notify(new TelegrammBot($data));
                    }
                }
//                DB::table('items')
//                    ->where('id', $item->id)
//                    ->update(['status' => 2]);
                $i++;
            }
        }
        Log::channel('send')->info('Отправлено сообщений -'.$i);
    }
}
