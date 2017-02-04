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
			'user_id' => '1',
			'renes_api_call' => 'http://147.27.14.3:11884/solarAPI/42.639656/18.395071/662/30/0/20/1010/13/250/-0.42/low/redlow/60/15/FreeStanding/98',
            'wunderground_api_call' => 'http://api.wunderground.com/api/6ddfa7fae5b58b38/hourly10day/q/42.63850447413804,18.397979345703106.json',
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
			'user_id' => '2',
			'renes_api_call' => 'http://147.27.14.3:11884/solarAPI/42.872837/18.436411/516/30/5/20/1010/13.4/255/-0.4/clear/redlow/60/15/FlatRoof/98',
            'wunderground_api_call' => 'http://api.wunderground.com/api/6ddfa7fae5b58b38/hourly10day/q/42.87269271882527,18.435401525878888.json',
        ];


		DB::table('locations')->insert($insert);
    }
}
