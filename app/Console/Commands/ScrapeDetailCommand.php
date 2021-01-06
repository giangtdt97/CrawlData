<?php

namespace App\Console\Commands;

use App\Scraper\truyenfullVn;
use Illuminate\Console\Command;

class ScrapeDetailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape_detail:start';

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
     * @return int
     */
    public function handle()
    {
        $bot = new truyenfullVn();
        $bot->scrape_detail();
        $this->info('Command Run successfully!');
    }
}
