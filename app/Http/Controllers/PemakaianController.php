<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pemakaian;
use App\Models\IndoDate;

class PemakaianController extends Controller
{
    public function __construct()
    {
        $this->middleware('pemakaian');
    }

    public function index(){
        return view('pemakaian.index',[
            'dataset'=>Pemakaian::data()
        ]);
    }

    public function fasilitas(Request $request, $fasilitas, $bulan){
        if($fasilitas == 'listrik'){
            $rekap = Pemakaian::rekapListrik($bulan);

            return view('pemakaian.listrik',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapListrik($rekap),
                'rincian'=>Pemakaian::rincianListrik($bulan),
            ]);
        }

        if($fasilitas == 'airbersih'){
            $rekap = Pemakaian::rekapAirBersih($bulan);

            return view('pemakaian.airbersih',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapAirBersih($rekap),
                'rincian'=>Pemakaian::rincianAirBersih($bulan),
            ]);
        }

        if($fasilitas == 'keamananipk'){
            $rekap = Pemakaian::rekapKeamananIpk($bulan);
            
            $time = strtotime($bulan);
            $bln = date("Y-m", strtotime("+1 month", $time));

            return view('pemakaian.keamananipk',[
                'bln'=>IndoDate::bulanB($bln,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapKeamananIpk($rekap),
                'rincian'=>Pemakaian::rincianKeamananIpk($bulan),
            ]);
        }

        if($fasilitas == 'kebersihan'){
            $rekap = Pemakaian::rekapKebersihan($bulan);
            
            $time = strtotime($bulan);
            $bln = date("Y-m", strtotime("+1 month", $time));

            return view('pemakaian.kebersihan',[
                'bln'=>IndoDate::bulanB($bln,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapKebersihan($rekap),
                'rincian'=>Pemakaian::rincianKebersihan($bulan),
            ]);
        }

        if($fasilitas == 'total'){
            $rekap = Pemakaian::rekapTotal($bulan);

            return view('pemakaian.total',[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Pemakaian::ttlRekapTotal($rekap),
                'rincian'=>Pemakaian::rincianTotal($bulan),
            ]);
        }

        if($fasilitas == 'diskon'){
            $rekapKeamananIpk = Pemakaian::rekapDisKeamananIpk($bulan);
            $rekapKebersihan  = Pemakaian::rekapDisKebersihan($bulan);
            $rekapListrik     = Pemakaian::rekapDisListrik($bulan);
            $rekapAirBersih   = Pemakaian::rekapDisAirBersih($bulan);
            
            $time = strtotime($bulan);
            $bln = date("Y-m", strtotime("+1 month", $time));

            return view('pemakaian.diskon',[
                'bln'=>IndoDate::bulanB($bln,' '),
                'bulan'=>IndoDate::bulanB($bulan,' '),
                'rekapKeamananIpk'=>$rekapKeamananIpk,
                'ttlRekapKeamananIpk'=>Pemakaian::ttlRekapDisKeamananIpk($rekapKeamananIpk),
                'rincianKeamananIpk'=>Pemakaian::rincianDisKeamananIpk($bulan),
                'rekapKebersihan'=>$rekapKebersihan,
                'ttlRekapKebersihan'=>Pemakaian::ttlRekapDisKebersihan($rekapKebersihan),
                'rincianKebersihan'=>Pemakaian::rincianDisKebersihan($bulan),
                'rekapListrik'=>$rekapListrik,
                'ttlRekapListrik'=>Pemakaian::ttlRekapDisListrik($rekapListrik),
                'rincianListrik'=>Pemakaian::rincianDisListrik($bulan),
                'rekapAirBersih'=>$rekapAirBersih,
                'ttlRekapAirBersih'=>Pemakaian::ttlRekapDisAirBersih($rekapAirBersih),
                'rincianAirBersih'=>Pemakaian::rincianDisAirBersih($bulan),
            ]);
        }
    }
}
