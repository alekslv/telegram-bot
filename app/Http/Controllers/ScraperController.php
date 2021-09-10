<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\Statement;

class ScraperController extends Controller
{

    public function index()
    {
        header('Content-Type: text/html; charset=utf-8');
        $client = new Client();
        try {
           $crawler = $client->request('GET', 'https://data.gov.ua/dataset/eda4e3cf-0dda-46a1-a78a-ee264ebbfe97');
        } catch (\InvalidArgumentException $e) {
            Log::channel('parser')->info('Сайт не отвечает. ' . $e->getMessage());
            exit();
        }
        try {
            $link = $crawler->filter('.resource-url-analytics')->link();
        } catch (\InvalidArgumentException $e) {
            Log::channel('parser')->info('Ссылка для скачивания csv не найдена. ' . $e->getMessage());
        }

        if (isset($link)) {
            // cылка на  док
            $uri = $link->getUri();

            // читаем и записуем
            try {
                $csv_str = file_get_contents($uri);

                // название нашего файла
                $fileName = public_path() . '/csv/datas.csv';
                $res = file_put_contents($fileName, $csv_str);

                $csv = Reader::createFromPath($fileName, 'r');

                $csv->setHeaderOffset(0);
                $i = 0;
                foreach ($csv as $key => $record) {
                    $state = $record["Стан"];
                    if ("Реєстрація учасників" == $state) {

                        $name = $record["Назва"];
                        $category = $record["Категорія"];
                        $place = $record["Місцезнаходження"];
                        $number = $record["Номер лота"];
                        $start_price = $record["Стартова ціна"];
                        $price = $record["Ціна продажу"];

                        $proceedings='';
                        if(isset($record["Проовадження"])){
                            $proceedings = $record["Проовадження"];
                        }

                        DB::table('items')->upsert(
                            [
                                [
                                    'name' => $name,
                                    'state' => $state,
                                    'category' => $category,
                                    'place' => $place,
                                    'number' => $number,
                                    'start_price' => $start_price,
                                    'price' => $price,
                                    'proceedings' => $proceedings,
                                    'created_at' => Carbon::now()
                                ],
                            ],
                            ['number'],
                            [
                                'name',
                                'state',
                                'category',
                                'place',
                                'start_price',
                                'price',
                                'proceedings',
                                'created_at'
                            ]
                        );
                        $i++;
                    } else {
                        continue;
                    }
                }

                Log::channel('parser')->info('Обновлена информация. Добавлено или обновлено- ' .$i);

            }
            catch (\InvalidArgumentException $e) {
                Log::channel('parser')->info('Неудалось считать файл csv. ' . $e->getMessage());
            }


        }
    }
}
