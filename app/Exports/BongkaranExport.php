<?php

namespace App\Exports;

use App\Models\IndoDate;
use App\Models\Pedagang;
use App\Models\TempatUsaha;
use Carbon\Carbon;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BongkaranExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $time = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

        $dataset = TempatUsaha::where('stt_bongkar','>',1)->orderBy('stt_bongkar','desc')->orderBy('kd_kontrol','asc')->get();

        return view('layanan.bongkaran.generate', [
            'time' => $time,
            'dataset' => $dataset,
        ]);
    }
}
