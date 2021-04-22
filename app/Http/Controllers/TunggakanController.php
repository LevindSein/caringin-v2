<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IndoDate;
use App\Models\Tunggakan;

class TunggakanController extends Controller
{
    public function __construct()
    {
        $this->middleware('tunggakan');
    }

    public function index(){
        return view('laporan.tunggakan.index',[
            'dataset'=>Tunggakan::data()
        ]);
    }

    public function fasilitas(Request $request){
        $fasilitas = $request->fasilitas;
        $bulan     = $request->hidden_value;

        if($fasilitas == 'listrik'){
            $rekap = Tunggakan::rekapListrik($bulan);

            return view('laporan.tunggakan.listrik',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Tunggakan::ttlRekapListrik($rekap),
                'rincian'=>Tunggakan::rincianListrik($bulan),
            ]);
        }

        if($fasilitas == 'airbersih'){
            $rekap = Tunggakan::rekapAirBersih($bulan);

            return view('laporan.tunggakan.airbersih',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Tunggakan::ttlRekapAirBersih($rekap),
                'rincian'=>Tunggakan::rincianAirBersih($bulan),
            ]);
        }

        if($fasilitas == 'keamananipk'){
            $rekap = Tunggakan::rekapKeamananIpk($bulan);
            
            $time = strtotime($bulan);
            $bln = date("Y-m", strtotime("+1 month", $time));

            return view('laporan.tunggakan.keamananipk',[
                'bln'=>IndoDate::bulanB($bln,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Tunggakan::ttlRekapKeamananIpk($rekap),
                'rincian'=>Tunggakan::rincianKeamananIpk($bulan),
            ]);
        }

        if($fasilitas == 'kebersihan'){
            $rekap = Tunggakan::rekapKebersihan($bulan);
            
            $time = strtotime($bulan);
            $bln = date("Y-m", strtotime("+1 month", $time));

            return view('laporan.tunggakan.kebersihan',[
                'bln'=>IndoDate::bulanB($bln,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Tunggakan::ttlRekapKebersihan($rekap),
                'rincian'=>Tunggakan::rincianKebersihan($bulan),
            ]);
        }
        
        if($fasilitas == 'airkotor'){
            return view('laporan.tunggakan.airkotor');
        }
            
        if($fasilitas == 'lain'){
            return view('laporan.tunggakan.lain');
        }

        if($fasilitas == 'total'){
            $rekap = Tunggakan::rekapTotal($bulan);

            return view('laporan.tunggakan.total',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Tunggakan::ttlRekapTotal($rekap),
                'rincian'=>Tunggakan::rincianTotal($bulan),
            ]);
        }
    }
}
