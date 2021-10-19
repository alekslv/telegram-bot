<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItemUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('item_user', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->index();
            $table->integer('user_id')->index();
            $table->integer('status')->default(1)->comment('Если 1- то отправлялась');
        });

        Schema::create('offset_user', function (Blueprint $table) {
            $table->id();
            $table->integer('offset')->default(0);
            $table->integer('user_id')->index();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('status')->default(0)->comment('0- неактив,1 - активный');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
