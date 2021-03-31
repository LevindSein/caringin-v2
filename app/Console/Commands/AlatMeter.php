<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\AlatListrik;
use App\Models\AlatAir;
use App\Models\TempatUsaha;
use App\Models\Tagihan;

class AlatMeter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:alatmeter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengambil data akhir dari alat meter';

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
        $dataset = AlatListrik::get();
        foreach($dataset as $d){
            $data = TempatUsaha::where('id_meteran_listrik', $d->id)->select('kd_kontrol')->first();
            if($data != NULL){
                $tagihan = Tagihan::where('kd_kontrol',$data->kd_kontrol)->orderBy('bln_pakai','desc')->first();
                if($tagihan != NULL){
                    if($tagihan->stt_listrik == 1)
                        $d->akhir = $tagihan->akhir_listrik;
                    else if($tagihan->stt_listrik === 0)
                        $d->akhir = $tagihan->awal_listrik;
                    $d->save();
                }
            }
        }

        $dataset = AlatAir::get();
        foreach($dataset as $d){
            $data = TempatUsaha::where('id_meteran_air', $d->id)->select('kd_kontrol')->first();
            if($data != NULL){
                $tagihan = Tagihan::where('kd_kontrol',$data->kd_kontrol)->orderBy('bln_pakai','desc')->first();
                if($tagihan != NULL){
                    if($tagihan->stt_airbersih == 1)
                        $d->akhir = $tagihan->akhir_airbersih;
                    else if($tagihan->stt_airbersih === 0)
                        $d->akhir = $tagihan->awal_airbersih;
                    $d->save();
                }
            }
        }
    }
}