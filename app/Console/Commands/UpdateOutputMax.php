<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;

class UpdateOutputMax extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:outputmax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update pv output max';

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
        $result = Location::updateOutputMax();
    }
}
