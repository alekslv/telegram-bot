<?php


namespace App\Services;

use App\Models\Item;
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

    /*
     * Обновление
     */
    static public function update_item($user,$items=null){
        if(is_null($items)){
            $items=Item::orderBy('id', 'ASC')->active()->get();
        }
        // удаляем все неотправленные
        DB::table('item_user')->where('user_id', $user->id)
            ->where('status',1)
            ->delete();
        // наново перезаписываем
        $user_regions = DB::table('region_user')
            ->where('user_id', $user->id)
            ->get();
        $user_categories = DB::table('category_user')
            ->where('user_id', $user->id)
            ->get();
        foreach ($items as $item){
            $res_place = $user_regions->pluck('region')->contains($item->place);
            $res_category = $user_categories->pluck('category')->contains($item->category);

            if ($res_place && $res_category) {
                $count=DB::table('item_user')
                    ->where('item_id',$item->id )
                    ->where('user_id',$user->id )
                    ->count();
                if($count==0){
                    DB::table('item_user')
                        ->insert([
                            ['item_id' => $item->id, 'user_id' => $user->id],
                        ]);
                }
            }
        }
    }


}
