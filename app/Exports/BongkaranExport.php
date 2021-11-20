<?php

namespace App\Exports;

use App\Models\IndoDate;
use App\Models\Pedagang;
use App\Models\TempatUsaha;
use App\Models\Tagihan;
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

        $today = Carbon::now();
        $today = strtotime($today);

        $dataset = TempatUsaha::where('stt_bongkar','>',3)->orderBy('stt_bongkar','desc')->orderBy('kd_kontrol','asc')->get();
        foreach ($dataset as $d){
            $data = Tagihan::where([['kd_kontrol',$d->kd_kontrol],['stt_lunas',0],['stt_publish',1]])->select('sel_tagihan','bln_tagihan')->get();
            $tagihan = 0;
            $banyak = 0;
            $max = 0;
            $bulan = 0;
            foreach ($data as $t) {
                $tagihan = $tagihan + $t->sel_tagihan;
                $banyak = $banyak + 1;

                $date1 = $t->bln_tagihan;
                $date2 = date('Y-m',$today);

                $ts1 = strtotime($date1);
                $ts2 = strtotime($date2);

                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);

                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

                $bulan = max($max, $diff);
                $max = $bulan;
            }
            $nama = Pedagang::find($d->id_pengguna);
            $nama = $nama->nama;

            $d['nama'] = $nama;
            $d['tagihan'] = $tagihan;
            $d['banyak'] = $banyak;
            $d['bulan'] = $bulan;
        }

        return view('layanan.bongkaran.generate', [
            'time' => $time,
            'dataset' => $dataset,
        ]);
    }
}
