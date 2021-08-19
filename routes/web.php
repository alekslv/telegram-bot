<?php

use Illuminate\Support\Facades\Route;
use \App\Notifications\TelegrammBot;
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

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/',[App\Http\Controllers\HomeController::class, 'index']);
// парсер
Route::get('/scraper',[App\Http\Controllers\ScraperController::class, 'index']);

// получить сообщения
Route::get('/updates',[App\Http\Controllers\UpdateController::class, 'index']);

// отправка
Route::get('/send_telegram', [App\Http\Controllers\SendtelegramController::class, 'index'])->name('Sendtelegram');
// лог
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');



//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
