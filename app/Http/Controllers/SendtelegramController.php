<?php

namespace App\Http\Controllers;

use App\Notifications\TelegrammBot;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class SendtelegramController extends Controller
{
    public function index()
    {

        $users = User::all();
        foreach ($users as $user) {
            if ($user->telegram_user_id) {
//                dump($user->name);
                Notification::route('telegram', $user->telegram_user_id)
                    ->notify(new TelegrammBot($user->name));
            }
        }

    }
}
