<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Forecast;

class weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wu:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get hourly 10 days weather forecast.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
		$jsonString = file_get_contents("http://api.wunderground.com/api/6ddfa7fae5b58b38/hourly10day/q/42.63850447413804,18.397979345703106.json");
		$jsonString1 = file_get_contents("http://api.wunderground.com/api/6ddfa7fae5b58b38/hourly10day/q/42.87269271882527,18.435401525878888.json");
		$parsedJson = json_decode($jsonString, true);
		$parsedJson1 = json_decode($jsonString1, true);
		
		$hours = $parsedJson['hourly_forecast'];
		$hours1 = $parsedJson1['hourly_forecast'];
		
		$year = 0;
		$month = 0;
		$day = 0;
		$hour = 0;
		$sky = 0;
		foreach ($hours as $h){
			$year = $h['FCTTIME']['year'];
			$month = $h['FCTTIME']['mon'];
			$day = $h['FCTTIME']['mday'];
			$hour = $h['FCTTIME']['hour'];
			$sky = $h['sky'];
			$pom = Forecast::where([['location_id', 1],['year', $year],['month', $month],['day', $day],['hour', $hour]])->first();
			$pom->cloud_coverage = $sky;
			if($pom->save()){
				echo $pom->id.$year.",".$month.",".$day.",".$hour.",".$sky."\n";
			}
			$pom->pv_output_correction = $pom->pv_output*(0.3 + (100 - $sky)*0.01);
			$pom->save();
		}
		
		$year1 = 0;
		$month1 = 0;
		$day1 = 0;
		$hour1 = 0;
		$sky1 = 0;
		foreach ($hours1 as $h){
			$year1 = $h['FCTTIME']['year'];
			$month1 = $h['FCTTIME']['mon'];
			$day1 = $h['FCTTIME']['mday'];
			$hour1 = $h['FCTTIME']['hour'];
			$sky1 = $h['sky'];
			$pom1 = Forecast::where([['location_id', 2],['year', $year1],['month', $month1],['day', $day1],['hour', $hour1]])->first();
			$pom1->cloud_coverage = $sky1;
			if($pom->save()){
				echo $pom1->id.$year1.",".$month1.",".$day1.",".$hour1.",".$sky1."\n";
			}
			$pom1->pv_output_correction = $pom1->pv_output*(0.3 + (100 - $sky1)*0.01);
			$pom1->save();
		}
		
		$controller = app()->make('App\Http\Controllers\EmailController');
        app()->call([$controller, 'send'], []);
		
		$this->info('Emails are sent successfully!');
    }
}