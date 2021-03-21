<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\Tagihan;
use App\Models\Simulasi;
use App\Models\IndoDate;

class SimulasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('simulasi');
    }

    public function index(){
        $dataTahun = Tagihan::select('thn_tagihan')
        ->groupBy('thn_tagihan')
        ->get();
        
        return view('simulasi.index',[
            'listrik'  => TarifListrik::first(),
            'airbersih'=> TarifAirBersih::first(),
            'tahun'    => $dataTahun,
        ]);
    }

    public function store(Request $request){
        $fasilitas = $request->hidden_id;
        $bulan     = $request->tahun."-".$request->bulan;

        if($fasilitas == 'listrik'){
            $beban = explode(',',$request->bebanListrik);
            $beban = implode('',$beban);

            $blok1 = explode(',',$request->blok1);
            $blok1 = implode('',$blok1);

            $blok2 = explode(',',$request->blok2);
            $blok2 = implode('',$blok2);
            
            $denda1 = explode(',',$request->denda1);
            $denda1 = implode('',$denda1);

            $data = [
                'trf_beban'        => $beban,
                'trf_blok1'        => $blok1,
                'trf_blok2'        => $blok2,
                'trf_standar'      => $request->waktu,
                'trf_bpju'         => $request->bpju,
                'trf_denda'        => $denda1,
                'trf_denda_lebih'  => $request->denda2,
                'trf_ppn'          => $request->ppnListrik
            ];

            $rekap = Simulasi::rekapListrik($bulan);
            return view('simulasi.listrik',[
                'bln'     =>IndoDate::bulanB($bulan,' '),
                'rekap'   =>$rekap,
                'ttlRekap'=>Simulasi::ttlRekapListrik($rekap),
                'rincian' =>Simulasi::rincianListrik($bulan),
                'bulan'   => $bulan,
                'data'    => $data
            ]);
        }
        
        if($fasilitas == 'airbersih'){
            $beban = explode(',',$request->bebanAir);
            $beban = implode('',$beban);

            $tarif1 = explode(',',$request->tarif1);
            $tarif1 = implode('',$tarif1);

            $tarif2 = explode(',',$request->tarif2);
            $tarif2 = implode('',$tarif2);
            
            $dendaAir = explode(',',$request->dendaAir);
            $dendaAir = implode('',$dendaAir);
            
            $pemeliharaan = explode(',',$request->pemeliharaan);
            $pemeliharaan = implode('',$pemeliharaan);

            $data = [
                'trf_beban'        => $beban,
                'trf_1'            => $tarif1,
                'trf_2'            => $tarif2,
                'trf_pemeliharaan' => $pemeliharaan,
                'trf_arkot'        => $request->arkot,
                'trf_denda'        => $dendaAir,
                'trf_ppn'          => $request->ppnAir
            ];

            $rekap = Simulasi::rekapAirBersih($bulan);
            return view('simulasi.airbersih',[
                'bln'     =>IndoDate::bulanB($bulan,' '),
                'rekap'   =>$rekap,
                'ttlRekap'=>Simulasi::ttlRekapAirBersih($rekap),
                'rincian' =>Simulasi::rincianAirBersih($bulan),
                'bulan'   => $bulan,
                'data'    => $data
            ]);
        }
    }
}
