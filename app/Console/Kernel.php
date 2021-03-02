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
        Commands\ScrapeStoryCommand::class,
        Commands\ScrapeChapterCommand::class,
        Commands\ScrapeDetailCommand::class,
        Commands\ScrapeChapterContenrCommand::class,
        Commands\ScrapeCategoryStoryCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

//        $filepath=base_path().'\storage\test.txt';
//        $schedule->command('scrape:start')->daily()->sendOutputTo($filepath);
        $schedule->command('scrape_story:start')->daily();
        $schedule->command('scrape_chapter:start')->daily();
        $schedule->command('scrape_detail:start')->daily();
        $schedule->command('scrape_pivot_id:start')->daily();
        $schedule->command('scrape_scrape_chapter_detail:start:start')->daily();
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
