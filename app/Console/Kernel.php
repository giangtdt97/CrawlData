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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $filepath=base_path().'\storage\test.txt';
        $schedule->command('scrape:start')->daily()->sendOutputTo($filepath);
        $schedule->command('scrape_story:start')->daily()->sendOutputTo($filepath);
        $schedule->command('scrape_chapter:start')->daily()->sendOutputTo($filepath);
//        $schedule->command('scrape_rating:start')->daily()->sendOutputTo($filepath);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
