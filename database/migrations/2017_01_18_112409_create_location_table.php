<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name', 512);
			$table->string('weather_station', 512);
			$table->string('gps_long', 255)->nullable();
			$table->string('gps_lat', 255)->nullable();
			$table->string('elevation',255)->nullable();
            $table->string('dc_size',255);
            $table->string('module_type', 255)->nullable();
			$table->string('array_type', 255)->nullable();
			$table->string('array_tilt', 255)->nullable();
            $table->string('array_azimuth',255)->nullable();
            $table->string('system_losses',255)->nullable();
			$table->string('inverter_efficiency', 255)->nullable();
			$table->string('dc_ac_ratio')->nullable();
			$table->string('renes_api_call')->nullable();
            $table->string('wunderground_api_call')->nullable();
			$table->integer('user_id');
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
        Schema::drop('locations');
    }
}
