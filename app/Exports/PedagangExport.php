<?php

namespace App\Exports;

use App\Models\IndoDate;
use App\Models\Pedagang;
use App\Models\TempatUsaha;
use Carbon\Carbon;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PedagangExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $time = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

        $dataset = Pedagang::where('role','nasabah')->orderBy('nama','asc')->get();
        foreach($dataset as $d){
            $pengguna = TempatUsaha::where('id_pengguna', $d->id)->select('kd_kontrol')->get();
            $d['pengguna'] = '';
            foreach($pengguna as $p){
                $d['pengguna'] .= $p->kd_kontrol.", ";
            }
            $d['pengguna'] = rtrim($d['pengguna'], ", ");

            $pemilik = TempatUsaha::where('id_pemilik', $d->id)->select('kd_kontrol')->get();
            $d['pemilik'] = '';
            foreach($pemilik as $p){
                $d['pemilik'] .= $p->kd_kontrol.", ";
            }
            $d['pemilik'] = rtrim($d['pemilik'], ", ");
        }

        return view('pedagang.generate', [
            'time' => $time,
            'dataset' => $dataset,
        ]);
    }
}
