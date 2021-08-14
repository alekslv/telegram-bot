<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->text('name');
            $table->text('state')->comment('Cтан');
            $table->text('category')->comment('Категорія');
            $table->text('place')->comment('Місцезнаходження');

            $table->integer('number')->unique()->comment('Номер лота');

            $table->string('start_price')->nullable()->comment('Стартова ціна');
            $table->string('price')->nullable()->comment('Ціна продаж');

            $table->string('proceedings')->nullable()->comment('Проовадження');

            $table->integer('status')->default(1)->comment('1= не отправлялось 2=отправлялось');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
