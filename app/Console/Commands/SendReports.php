<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForecastReport;

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
            $bcc = ['zv1985@gmail.com', 'vladan.mastilovic@gmail.com'];
            $emails = [];
            if (!empty($l->user->email)) {
                $emails[] = $l->user->email;
            }
            if (!empty($l->user->email1)) {
                $emails[] = $l->user->email1;
            }
            
            if (empty($emails)) continue;

            Mail::to($emails)
                ->bcc($bcc)
                ->send(new ForecastReport($data));
        }
    }
}
