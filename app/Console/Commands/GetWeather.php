<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Location;

class GetWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:weather';

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
        $result = Location::updateWeather();
        $this->info('Completed!');
        //var_dump($result);
        mail('zv1985@gmail.com', 'Weather cron', "Status: {$result['status']}, Message: {$result['message']}");
    }
}
