<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function index()
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $bot = new \TelegramBot\Api\Client($token);

//        $date=Carbon::now();
//        Log::channel('hook')->info($date);

        $bot->command('help', function ($message) use ($bot) {
            $date=Carbon::now();
            Log::channel('hook')->info('help  '.$date);
//            Log::channel('hook')->info($message->getChat()->getId());
            $answer = 'Добро пожаловать!';
            $bot->sendMessage($message->getChat()->getId(), $answer);
        });

        $bot->command('start1', function ($message) use ($bot) {
            $date=Carbon::now();
            Log::channel('hook')->info('start1 '.$date);
//            Log::channel('hook')->info($message->getChat()->getId());
            $answer = 'Добро пожаловать!';
            $bot->sendMessage($message->getChat()->getId(), $answer);
        });

        $bot->command('start2', function ($message) use ($bot) {
            $date=Carbon::now();
            Log::channel('hook')->info('start2 '.$date);
//            Log::channel('hook')->info($message->getChat()->getId());
            $answer = 'Добро пожаловать!';
            $bot->sendMessage($message->getChat()->getId(), $answer);
        });


    }
}
