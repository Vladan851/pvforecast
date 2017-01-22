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
		$parsedJson = json_decode($jsonString, true);
		
		$hours = $parsedJson['hourly_forecast'];
		
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
			$pom = Forecast::where([['year', $year],['month', $month],['day', $day],['hour', $hour]])->first();
			$pom->cloud_coverage = $sky;
			if($pom->save()){
				echo $pom->id.$year.",".$month.",".$day.",".$hour.",".$sky."\n";
			}
			$pom->pv_output_correction = $pom->pv_output*(0.3 + (100 - $sky)*0.01);
			$pom->save();
		}
		
		$controller = app()->make('App\Http\Controllers\EmailController');
        app()->call([$controller, 'send'], []);
		
		$this->info('Emails are sent successfully!');
    }
}