<?php


namespace App\Telegram\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{

    protected $name = 'help';

    protected $aliases = ['listcommands2'];

    protected $description = 'Help command, Get a list of all commands';


    public function handle()

    {
        $response = $this->getUpdate();
        $telegram_user_id = $response->getMessage()->from->id;
        $chat_user_id = $response->getMessage()->chat->id;

        $message = $response->message;
        $text = $message->text;

        // старт !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        if ($text == '/start') {

            $user = DB::table('users')
                ->where('telegram_user_id', $telegram_user_id)
                ->first();
            if ($user) {
                $keyboard = [
                    ['/category'],
                    ['/region'],
                ];
                $reply_markup = Keyboard::make([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]);
                /*
                $btn1 = Keyboard::inlineButton([
                    'text' => 'category',
//                    'callback_data' => '/category',
                    'callback_data' => 'data',
                    'url' => 'https://t.me/all2all_bot?category=c'
//                    'request_contact' => true,
                ]);
                $contact_keyboard = Keyboard::make([
                    'keyboard' => [[$btn1]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]);
                */
                $user_id = $user->id;
                $response = $this->replyWithMessage([
                    'text' => 'Start' ,
                    'reply_markup' => $reply_markup,
                    'chat_id' => $chat_user_id
                ]);

            }else{

                $telegram_user_id = $response->getMessage()->from->id;
                $chat_user_id = $response->getMessage()->chat->id;
                $time = Carbon::now();
                $fio = '';
                if (isset($response->getMessage()->from->first_name)) {
                    $fio .= $response->getMessage()->from->first_name;
                }
                if (isset($response->getMessage()->from->last_name)) {
                    $fio .= ' ' . $response->getMessage()->from->last_name;
                }
                $username = $response->getMessage()->from->username;
                DB::table('users')->insert([
                    'telegram_user_id' => $telegram_user_id,
                    'name' => $username,
                    'fio' => $fio,
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
                $text = 'Привіт.Налаштування:'.chr(10).chr(10);
                $text .= '/start - Додати або видалити регіон'.chr(10);
                $text .= '/category  - Додати або видалити категорію'.chr(10).chr(10);
                $response = $this->replyWithMessage([
                    'text' => $text ,
                    'chat_id' => $chat_user_id
                ]);

            }

        }
        // регионы !!!!!!!!!!!!!!!!!!!
        if ($text == '/region') {
            $keyboard = [
                ['Додати або видалити всі регіони (якщо є регіони-все очищається)'],
                ['м.Київ', 'Київська обл.'],
                ['Вінницька обл.', 'Волинська обл.', 'Дніпропетровська обл.'],
                ['Донецька обл.', 'Житомирська обл.', 'Закарпатська обл.'],
                ['Запорізька обл.', 'Івано-Франківська обл.', 'Кіровоградська обл.'],
                ['Луганська обл.', 'Львівська обл.'],
                ['Миколаївська обл.','Одеська обл.','Полтавська обл.'],
                ['Рівненська обл.','Сумська обл.','Тернопільська обл.'],
                ['Харківська обл.','Херсонська обл.','Хмельницька обл.'],
                ['Черкаська обл.','Чернівецька обл.','Чернігівська обл.']
            ];
            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);

            // **************************пользователя ********************************
            $region_text = chr(10) . 'Ваші регіони:' . chr(10);
            $user = DB::table('users')
                ->where('telegram_user_id', $telegram_user_id)
                ->first();
            if ($user) {
                $user_id = $user->id;
                $region_users = DB::table('region_user')
                    ->where('user_id', $user_id)
                    ->get();
                if ($region_users) {
                    foreach ($region_users as $region_user) {
                        $region_text .= $region_user->region . chr(10);
                    }
                }
                $response = $this->replyWithMessage([
                    'text' => 'Додати або видалити регіон' . $region_text,
                    'reply_markup' => $reply_markup,
                    'chat_id' => $chat_user_id
                ]);
            }
        }
        // категории
        if ($text == '/category') {
            $category = config('category');
            $keyboard = [
                ['Додати  або видалити всі категорії (якщо є категорії-все очищається)'],
                [
                    'Автобуси',
                    'Аксесуари',
                    'Аксесуари та інше',
                ],
                [
                    'Аукціон із різнотипних товарів',
                    'Банківське обладнання',
                    'Брухт',
                ],

                [
                    'Будівельні матеріали',
                    'Будівлі',
                    'Вантажні автомобілі',
                ],

                [
                    'Велика побутова техніка',
                    'Взуття',
                    'Водний транспорт',
                ],
                [
                    'Вугілля',
                    'Гаражі/стоянки',
                    'Господарчі товари',
                ],
                [
                    'Житлова нерухомість',
                    'Запчастини/аксесуари',
                    'Земельні ділянки',
                ],

                [
                    'Інструменти',
                    'Інша техніка',
                    'Інше',
                ],
                [
                    'Канцелярські товари',
                    'Кліматична  техніка',
                    'Комерційна нерухомість',
                ],
                [
                    "Комп'ютери",
                    "Комп'ютерна і офісна техніка",
                    'Комплектуючі',
                ],

                [
                    'Легкові автомобілі',
                    'Мала побутова техніка',
                    'Меблі',
                ],
                [
                    'Мобільні телефони',
                    'Монітори',
                    'Мототранспорт',
                ],
                [
                    'Накопичувачі',
                    'Недобудована',
                    'Нежитлове приміщення',
                ],
                [
                    'Ноутбуки',
                    'Обладнання',
                    'Одяг',
                ],
                [
                    'Оргтехніка',
                    'Планшети, електронні книги',
                    'Причепи',
                ],
                [
                    'Промислова нерухомість',
                    'Рації/радіостанції',
                    'Сад і дім',
                ],
                [
                    'Серверне обладнання',
                    'Сировина',
                    'Сільгосп продукція',
                ],
                [
                    'Сільгосптехніка',
                    'Спецтехніка',
                    'ТВ, Фото, Відео, Аудіо',
                ],
                [
                    'Телевізори',
                    'Товари для дому',
                ],
            ];
            $reply_markup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);

            // **************************пользователя ********************************
            $category_text = chr(10) . 'Ваші категорії:' . chr(10);
            $user = DB::table('users')
                ->where('telegram_user_id', $telegram_user_id)
                ->first();
            if ($user) {
                $user_id = $user->id;
                $category_users = DB::table('category_user')
                    ->where('user_id', $user_id)
                    ->get();
                if ($category_users) {
                    foreach ($category_users as $category_user) {
                        $category_text .= $category_user->category . chr(10);
                    }
                }
            }
            $response = $this->replyWithMessage([
                'text' => 'Додати категорію' . $category_text,
                'reply_markup' => $reply_markup,
                'chat_id' => $chat_user_id
            ]);
        }
        //******************************************************

    }
}
