<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForecastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('forecast', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('month');
            $table->integer('day');
            $table->integer('hour');
            $table->double('pv_output',10,2);
            $table->integer('location_id');
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
        //
        Schema::drop('forecast');
    }
}
