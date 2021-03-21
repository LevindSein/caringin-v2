<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete laravel.log in storage';

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
        exec('rm ' . storage_path('logs/*.log'));
    }
}
