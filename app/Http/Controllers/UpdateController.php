<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Update;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class UpdateController extends Controller
{
    public function index()
    {

        $TOKEN = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot" . $TOKEN . "/getUpdates";
        $update = Update::latest()->first();
        if ($update) {
            $params = [
                'offset' => $update->update_id
            ];
            $url=$url.'?'.http_build_query($params);
        }
        $getUpdates = file_get_contents($url);
        $response = json_decode($getUpdates, true);
        if ($response['ok']) {
            $time=Carbon::now();
            foreach ($response["result"] as $item) {
                $from = $item['message']['from'];
                $fio = '';
                if (isset($from["first_name"])) {
                    $fio .= $from["first_name"];
                }
                if (isset($from["last_name"])) {
                    $fio .= ' ' . $from["last_name"];
                }
                $username='';
                if(isset($from['username'])){
                    $username=$from['username'];
                }
                DB::table('users')->upsert(
                    [
                        [
                            'telegram_user_id' => $from['id'],
                            'name' => $username,
                            'fio' => $fio,
                            'created_at' => $time,
                            'updated_at' => $time,
                        ],
                    ],
                    ['telegram_user_id'],
                    [
                        'name',
                        'fio',
                        'created_at',
                        'updated_at'
                    ]
                );
            }
            $array_key_last=array_key_last($response["result"]);
            $last_update_id=$response["result"][$array_key_last]['update_id'];

            DB::table('updates')->insert([
                [
                    'update_id' => $last_update_id,
                    'created_at' =>$time
                ],
            ]);
            Log::channel('users')->info('Добавлено или обновлено- ' .count($response["result"]).'. Последнее update_id- '.$last_update_id);
        }

    }
}
