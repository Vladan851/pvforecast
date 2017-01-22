<?php

use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$insert = [];
        $insert[0] = [
            'name' => 'Turmenti Trebinje',
            'weather_station' => 'Podgorica MNE',
            'gps_long' => '19.25',
            'gps_lat' => '42.37',
            'elevation' => '33',
            'dc_size' => '250',
            'module_type' => 'Standard',
            'array_type' => 'Fixed (open rack)',
            'array_tilt' => '30',
            'array_azimuth' => '180',
            'system_losses' => '15.79',
            'inverter_efficiency' => '98',
            'dc_ac_ratio' => '1.1',
			'user_id' => '1'
        ];
		$insert[1] = [
            'name' => 'Solar 1 Bileca',
            'weather_station' => 'Podgorica MNE',
            'gps_long' => '19.25',
            'gps_lat' => '42.37',
            'elevation' => '33',
            'dc_size' => '250',
            'module_type' => 'Standard',
            'array_type' => 'Fixed (open rack)',
            'array_tilt' => '30',
            'array_azimuth' => '180',
            'system_losses' => '15.79',
            'inverter_efficiency' => '98',
            'dc_ac_ratio' => '1.1',
			'user_id' => '2'
        ];
		
		
		DB::table('locations')->insert($insert);
    }
}
