<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoginLog;

class CronLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Riwayat Login';

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
        if(LoginLog::count() > 10000){
            LoginLog::orderBy('id','asc')->limit(3000)->delete();
        }
    }
}
