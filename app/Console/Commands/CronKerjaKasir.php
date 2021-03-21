<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class CronKerjaKasir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:kerjakasir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set time for Kasir Work at daily';

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
        $users = User::where('role','kasir')->get();
        if($users != NULL){
            foreach($users as $user){
                $user->stt_aktif = 1;
                $user->save();
            }
            \Log::info('Shift 1 Kasir Fine');
        }
    }
}
