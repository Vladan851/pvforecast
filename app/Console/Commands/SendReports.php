<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use Illuminate\Support\Facades\Mail;

class SendReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $locations = Location::all();
        foreach ($locations as $l) {
            $data = $l->getReportData();
            $emails = ['zv1985@gmail.com', 'vladan.mastilovic@gmail.com'];
            Mail::to($emails)
                ->bcc([])
                ->send(new ForecastReport($data));
        }
    }
}
