<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Models\IndoDate;
use App\Models\Pedagang;
use App\Models\TempatUsaha;
use Carbon\Carbon;

class TempatPasif implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $time = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

        $dataset = TempatUsaha::where('stt_tempat',2)->orderBy('kd_kontrol','asc')->get();
        foreach($dataset as $d){
            try{
                $pengguna = Pedagang::findOrFail($d->id_pengguna);
                $d['pengguna'] = $pengguna->nama;
            }catch(ModelNotFoundException $e){
                $d['pengguna'] = '';
            }

            $fasilitas = '';
            if(!is_null($d->trf_listrik)){
                $fasilitas .= 'Listrik, ';
            }

            if(!is_null($d->trf_airbersih)){
                $fasilitas .= 'Air Bersih, ';
            }

            if(!is_null($d->trf_keamananipk)){
                $fasilitas .= 'Keamanan & IPK, ';
            }

            if(!is_null($d->trf_kebersihan)){
                $fasilitas .= 'Kebersihan, ';
            }

            if(!is_null($d->trf_airkotor)){
                $fasilitas .= 'Air Kotor, ';
            }

            if(!is_null($d->trf_lain)){
                $fasilitas .= 'Lain - Lain, ';
            }

            $d['fasilitas'] = rtrim($fasilitas, ", ");
        }

        return view('potensi.tempatusaha.pasif', [
            'time' => $time,
            'dataset' => $dataset,
        ]);
    }
}
