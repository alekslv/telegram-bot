<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use \App\Notifications\TelegrammBot;
use Telegram\Bot\Laravel\Facades\Telegram;

use \App\Services\RegionService;
use \App\Services\UserService;
use \App\Services\CommandService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::view('/', 'welcome');

// парсер
Route::get('/scraper', [App\Http\Controllers\ScraperController::class, 'index']);

// получить сообщения
//Route::get('/updates', [App\Http\Controllers\UpdateController::class, 'index']);

// отправка
Route::get('/send_telegram', [App\Http\Controllers\SendtelegramController::class, 'index'])->name('Sendtelegram');

// очистка раз месяц таблиц
Route::get('/clear', [App\Http\Controllers\ClearController::class, 'index']);

// лог
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

// вебхук
// getWebhookInfo
Route::post('/getWebhookInfo', function () {

    $region = config('region');
    $category = config('category');

    $response = Telegram::getWebhookUpdates();
    $message = $response->message;
    $text = $message->text;

    if (isset($text) && !empty($text)) {
        if ($text == '/start' || $text == '/category'|| $text == '/region') {
            $update = Telegram::commandsHandler(true);
        } elseif (in_array($text, $region)) {
            //выбор региона
            RegionService::region_add($response);
        } elseif (in_array($text, $category)) {
            RegionService::category_add($response);
        } elseif ($text == 'Додати або видалити всі регіони (якщо є регіони-все очищається)') {
            RegionService::region_all($response);
        } elseif ($text == 'Додати  або видалити всі категорії (якщо є категорії-все очищається)') {
            RegionService::category_all($response);
        } elseif ($text == 'Вибрати регіони') {
            CommandService::region();
        }elseif ($text == 'Вибрати категорії') {
            CommandService::category();
        }elseif ($text == 'Назад') {
            CommandService::back();
        }
    }
    return 'ok';
});

Auth::routes();
