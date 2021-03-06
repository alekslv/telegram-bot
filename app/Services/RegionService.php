<?php


namespace App\Services;

use App\Services\CommandService;
use App\Services\UserService;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class RegionService
{

    static public function region_add($response)
    {

        $telegram_user_id = $response->message->from->id;
        $time = Carbon::now();

        $username = $response->message->from->username;
        $fio = $response->message->from->username;

        DB::table('users')->upsert(
            [
                [
                    'telegram_user_id' => $telegram_user_id,
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
        $user = DB::table('users')
            ->where('telegram_user_id', $telegram_user_id)
            ->first();

        if ($user) {
            $user_id = $user->id;
            //******************
            $message = $response->message;
            $text = $message->text;

            $user_region_count = DB::table('region_user')
                ->where('user_id', $user_id)
                ->where('region', $text)
                ->count();

            if ($user_region_count > 0) {
                // если есть удалить
                DB::table('region_user')
                    ->where('user_id', $user_id)
                    ->where('region', $text)
                    ->delete();
            } else {
                // если нету региона
                DB::table('region_user')->insert([
                    'user_id' => $user_id,
                    'region' => $text
                ]);
            }

            CommandService::region();
            UserService::update_item($user);
        }
    }

    static public function category_add($response)
    {

        $telegram_user_id = $response->message->from->id;
        $time = Carbon::now();

        $username = $response->message->from->username;
        $fio = $response->message->from->username;


        DB::table('users')->upsert(
            [
                [
                    'telegram_user_id' => $telegram_user_id,
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
        $user = DB::table('users')
            ->where('telegram_user_id', $telegram_user_id)
            ->first();

        if ($user) {
            $user_id = $user->id;
            //******************
            $message = $response->message;
            $text = $message->text;

            $user_category_count = DB::table('category_user')
                ->where('user_id', $user_id)
                ->where('category', $text)
                ->count();

            if ($user_category_count > 0) {
                // если есть удалить
                DB::table('category_user')
                    ->where('user_id', $user_id)
                    ->where('category', $text)
                    ->delete();
            } else {
                // если нету категории
                DB::table('category_user')->insert([
                    'user_id' => $user_id,
                    'category' => $text
                ]);
            }
            CommandService::category();
            UserService::update_item($user);
            Log::channel('hook')->info('Update ' . $user->id);
        }
    }

    // добавить все регионы
    static public function region_all($response)
    {

        $telegram_user_id = $response->message->from->id;
        $time = Carbon::now();
        $username = $response->message->from->username;
        $fio = $response->message->from->username;

        DB::table('users')->upsert(
            [
                [
                    'telegram_user_id' => $telegram_user_id,
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
        $user = DB::table('users')
            ->where('telegram_user_id', $telegram_user_id)
            ->first();
        if ($user) {
            $user_id = $user->id;
            $user_region_count = DB::table('region_user')
                ->where('user_id', $user_id)
                ->count();

            DB::table('region_user')
                ->where('user_id', $user_id)
                ->delete();

            if ($user_region_count > 0) {
                // если были регионы- то новые не  добавляем
                // очищаем все зависимости item_user


            } else {
                // если пусто
                $regions = config('region');
                foreach ($regions as $region) {
                    DB::table('region_user')->insert([
                        'user_id' => $user_id,
                        'region' => $region
                    ]);
                }
            }
            CommandService::region();
            UserService::update_item($user);
            Log::channel('hook')->info('Update ' . $user->id);
        }
    }

    // добавить все категории

    static public function category_all($response)
    {

        $telegram_user_id = $response->message->from->id;
        $time = Carbon::now();

        $username = $response->message->from->username;
        $fio = $response->message->from->username;

        DB::table('users')->upsert(
            [
                [
                    'telegram_user_id' => $telegram_user_id,
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
        $user = DB::table('users')
            ->where('telegram_user_id', $telegram_user_id)
            ->first();
        if ($user) {
            $user_id = $user->id;

            $user_category_count = DB::table('category_user')
                ->where('user_id', $user_id)
                ->count();

            DB::table('category_user')
                ->where('user_id', $user_id)
                ->delete();

            if ($user_category_count > 0) {
                //если есть категории
                // то чисто очищаем
            } else {
                $categories = config('category');
                foreach ($categories as $category) {
                    DB::table('category_user')->insert([
                        'user_id' => $user_id,
                        'category' => $category
                    ]);
                }
            }
            CommandService::category();
            UserService::update_item($user);
            Log::channel('hook')->info('Update ' . $user->id);
        }
    }

}
