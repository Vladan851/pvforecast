<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\GetRenes::class,
		Commands\GetWeather::class,
        Commands\SendReports::class,
		Commands\UpdateOutputMax::class,
    ];



    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->exec('php -d register_argc_argv=On /home/misterz1/public_html/pvforecast/artisan forecast:weather')->cron('* * * * *');

        //$schedule->exec('php -d register_argc_argv=On /home/misterz1/public_html/pvforecast/artisan forecast:renes')->hourlyAt(7);

        //$schedule->exec('php -d register_argc_argv=On /home/misterz1/public_html/pvforecast/artisan forecast:reports')->cron('30 * * * *');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');

    }
}
