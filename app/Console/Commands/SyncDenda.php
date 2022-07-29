<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class SyncDenda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:denda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi Tanggal Denda';

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
        DB::table('tagihan')->where('stt_lunas', 0)
            ->chunkById(100, function ($tagihan) {
                foreach ($tagihan as $t) {
                    DB::table('tagihan')
                        ->where('id', $t->id)
                        ->update(['tgl_expired' => Carbon::parse($t->tgl_expired)->setDay(15)->format('Y-m-d')]);
                }
            });
    }
}
