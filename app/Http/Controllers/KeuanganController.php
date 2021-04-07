<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\Dashboard;
use App\Models\TempatUsaha;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\Keuangan;
use App\Models\Pembayaran;
use App\Models\Harian;

use Carbon\Carbon;
use App\Models\IndoDate;
use DataTables;
use Exception;

class KeuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('keuangan');
    }

    public function tagihan(Request $request, $fasilitas){
        $now = date("Y-m-d",strtotime(Carbon::now()));
        $check = date("Y-m-23",strtotime(Carbon::now()));

        if($now < $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime(Carbon::now()));
        }
        else if($now >= $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime("+1 month", $time));
        }

        if($request->periode !== NULL || $request->periode != '')
            Session::put('periodetagihan',$request->periode);
        else
            Session::put('periodetagihan',$periode);

        $fas = Session::put("fasilitas",$fasilitas);

        if(request()->ajax()){
            if(Session::get("fasilitas") != 'tagihan')
                $data = Tagihan::where([['bln_tagihan',Session::get('periodetagihan')],["stt_$fasilitas",'!=',NULL]]);
            else
                $data = Tagihan::where('bln_tagihan',Session::get('periodetagihan'));
            return DataTables::of($data)
            ->editColumn("ttl_$fasilitas", function ($data) {
                $data['fasilitas'] = Session::get("fasilitas");
                if($data->fasilitas == 'listrik')
                    $hasil = $data->ttl_listrik;
                else if($data->fasilitas == 'airbersih')
                    $hasil = $data->ttl_airbersih;
                else if($data->fasilitas == 'keamananipk')
                    $hasil = $data->ttl_keamananipk;
                elseif($data->fasilitas == 'kebersihan')
                    $hasil = $data->ttl_kebersihan;
                else if($data->fasilitas == 'airkotor')
                    $hasil = $data->ttl_airkotor;
                else if($data->fasilitas == 'lain')
                    $hasil = $data->ttl_lain;
                else
                    $hasil = $data->ttl_tagihan;
                $hasil = number_format($hasil);
                return "<span style='color:#172b4d;'>$hasil</span></a>";
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns([
                'show',
                "ttl_$fasilitas",
            ])
            ->make(true);
        }
        return view("keuangan.tagihan.$fasilitas",[
            "periode" => IndoDate::bulan(Session::get("periodetagihan"), " ")
        ]);
    }

    public function tagihanGenerate(Request $request, $data){
        if(request()->ajax()){

        }
    }

    public function tunggakan(Request $request, $fasilitas){
        $now = date("Y-m-d",strtotime(Carbon::now()));
        $check = date("Y-m-23",strtotime(Carbon::now()));

        if($now < $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime(Carbon::now()));
        }
        else if($now >= $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime("+1 month", $time));
        }

        if($request->periode !== NULL || $request->periode != '')
            Session::put('periodetagihan',$request->periode);
        else
            Session::put('periodetagihan',$periode);

        $fas = Session::put("fasilitas",$fasilitas);

        if(request()->ajax()){
            if(Session::get("fasilitas") != 'tagihan')
                $data = Tagihan::where([['bln_tagihan',Session::get('periodetagihan')],["sel_$fasilitas",'>',0],["stt_$fasilitas",'!=',NULL]]);
            else
                $data = Tagihan::where([['bln_tagihan',Session::get('periodetagihan')],["sel_$fasilitas",'>',0]]);
            return DataTables::of($data)
            ->editColumn("sel_$fasilitas", function ($data) {
                $data['fasilitas'] = Session::get("fasilitas");
                if($data->fasilitas == 'listrik')
                    $hasil = $data->sel_listrik;
                else if($data->fasilitas == 'airbersih')
                    $hasil = $data->sel_airbersih;
                else if($data->fasilitas == 'keamananipk')
                    $hasil = $data->sel_keamananipk;
                elseif($data->fasilitas == 'kebersihan')
                    $hasil = $data->sel_kebersihan;
                else if($data->fasilitas == 'airkotor')
                    $hasil = $data->sel_airkotor;
                else if($data->fasilitas == 'lain')
                    $hasil = $data->sel_lain;
                else
                    $hasil = $data->sel_tagihan;
                $hasil = number_format($hasil);
                return "<span style='color:#172b4d;'>$hasil</span></a>";
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns([
                'show',
                "sel_$fasilitas",
            ])
            ->make(true);
        }
        return view("keuangan.tunggakan.$fasilitas",[
            "periode" => IndoDate::bulan(Session::get("periodetagihan"), " ")
        ]);
    }

    public function tunggakanGenerate(Request $request, $data){
        if(request()->ajax()){

        }
    }

    public function pendapatan($data){
        if(request()->ajax())
        {
            if($data == 'harian'){
                $data = Pembayaran::orderBy('tgl_bayar','desc');
                return DataTables::of($data)
                ->editColumn('realisasi', function ($data) {
                    return number_format($data->realisasi);
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }

            else if($data == 'bulanan'){
                $data = Pembayaran::select('bln_bayar')->groupBy('bln_bayar')->orderBy('bln_bayar','desc');
                return DataTables::of($data)
                ->addColumn('realisasi', function($data){
                    $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->bln_bayar.'" nama="'.$data->bln_bayar.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }

            else if($data == 'tahunan'){
                $data = Pembayaran::select('thn_bayar')->groupBy('thn_bayar')->orderBy('thn_bayar','desc');
                return DataTables::of($data)
                ->addColumn('realisasi', function($data){
                    $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->thn_bayar.'" nama="'.$data->thn_bayar.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }
        }
    
        return view("keuangan.pendapatan.$data");
    }

    public function pendapatanGenerate(Request $request, $data){
        if(request()->ajax()){

        }
    }

    public function rekap($data){
        if(request()->ajax())
        {
            if($data == 'sisa'){
                $data = Tagihan::select('blok')->groupBy('blok')->orderBy('blok','asc');
                return DataTables::of($data)
                ->addColumn('tagihan', function ($data) {
                    $tagihan = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$data->blok]])
                    ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->blok.'" nama="'.$data->blok.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }

            else if($data == 'akhir'){
                $data = Pembayaran::select('bln_bayar')->groupBy('bln_bayar')->orderBy('bln_bayar','desc');
                return DataTables::of($data)
                ->addColumn('realisasi', function($data){
                    $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->bln_bayar.'" nama="'.$data->bln_bayar.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }
        }
    
        return view("keuangan.rekap.$data");
    }

    public function rekapGenerate(Request $request, $data){

    }
}
