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
        'App\Console\Commands\DatabaseBackUp',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cron:log')->monthlyOn(1, '01:00');
        $schedule->command('cron:tagihan')->monthlyOn(25, '01:00');
        $schedule->command('cron:denda')->dailyAt('00:01');
        $schedule->command('cron:kerjakasir')->dailyAt('06:00');
        $schedule->command('cron:tutupkasir')->dailyAt('16:00');
        $schedule->command('cron:login')->dailyAt('03:00');
        $schedule->command('cron:backup')->dailyAt('05:00');
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
