<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;

class GetRenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:renes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get solar prediction from RENES';

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
        $this->info('started');
        $result = Location::updateRenes();
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone("Europe/Sarajevo"));

        //var_dump($result);
        mail('zv1985@gmail.com', 'Renes cron ' . $date->format('Y-m-d H:i:s'), "Status: {$result['status']}, Message: {$result['message']}");
    }
}
