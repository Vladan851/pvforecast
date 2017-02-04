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
        Schema::create('forecasts', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('location_id');
			$table->integer('year');
			$table->integer('month');
            $table->integer('day');
            $table->integer('hour');
            $table->integer('cloud_coverage')->nullable();
            $table->integer('temperature')->nullable();
            
            // Yield fields (Wh)
			$table->double('pv_output', 10, 2);
			$table->double('pv_output_correction', 10, 2)->nullable();
			$table->double('pv_output_renes', 10, 2)->nullable();
			$table->double('pv_output_ai', 10, 2)->nullable();
			$table->double('pv_output_actual', 10, 2)->nullable();

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
        Schema::drop('forecasts');
    }
}
