<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Exception;

use App\Models\Tagihan;
use App\Models\Penghapusan;
use App\Models\TempatUsaha;
use App\Models\IndoDate;

class DataUsahaController extends Controller
{
    public function __construct()
    {
        $this->middleware('datausaha');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan');
            return DataTables::of($data)
            ->addColumn('ttl_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->editColumn('bln_tagihan', function($data){
                return IndoDate::bulanS($data->bln_tagihan,' ');
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.$data->bln_tagihan.'" nama="'.IndoDate::bulan($data->bln_tagihan," ").'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns(['show'])
            ->make(true);
        }
        return view('datausaha.index');
    }

    public function tunggakan()
    {
        if(request()->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->where('sel_tagihan','>',0);
            return DataTables::of($data)
            ->addColumn('sel_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->editColumn('bln_tagihan', function($data){
                return IndoDate::bulanS($data->bln_tagihan,' ');
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.$data->bln_tagihan.'" nama="'.IndoDate::bulan($data->bln_tagihan," ").'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns(['show'])
            ->make(true);
        }
    }

    public function pendapatan()
    {
        if(request()->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->where('rea_tagihan','>',0);
            return DataTables::of($data)
            ->addColumn('rea_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->editColumn('bln_tagihan', function($data){
                return IndoDate::bulanS($data->bln_tagihan,' ');
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.$data->bln_tagihan.'" nama="'.IndoDate::bulan($data->bln_tagihan," ").'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns(['show'])
            ->make(true);
        }
    }

    public function show($bulan){
        $data = Tagihan::where('bln_tagihan',$bulan)
        ->select(
        DB::raw('SUM(ttl_listrik) as tagihan_listrik'),
        DB::raw('SUM(rea_listrik) as realisasi_listrik'),
        DB::raw('SUM(sel_listrik) as selisih_listrik'),
        DB::raw('SUM(dis_listrik) as diskon_listrik'),
        DB::raw('SUM(den_listrik) as denda_listrik'),
        DB::raw('SUM(ttl_airbersih) as tagihan_airbersih'),
        DB::raw('SUM(rea_airbersih) as realisasi_airbersih'),
        DB::raw('SUM(sel_airbersih) as selisih_airbersih'),
        DB::raw('SUM(dis_airbersih) as diskon_airbersih'),
        DB::raw('SUM(den_airbersih) as denda_airbersih'),
        DB::raw('SUM(ttl_keamananipk) as tagihan_keamananipk'),
        DB::raw('SUM(rea_keamananipk) as realisasi_keamananipk'),
        DB::raw('SUM(sel_keamananipk) as selisih_keamananipk'),
        DB::raw('SUM(dis_keamananipk) as diskon_keamananipk'),
        DB::raw('SUM(ttl_kebersihan) as tagihan_kebersihan'),
        DB::raw('SUM(rea_kebersihan) as realisasi_kebersihan'),
        DB::raw('SUM(sel_kebersihan) as selisih_kebersihan'),
        DB::raw('SUM(dis_kebersihan) as diskon_kebersihan'),
        DB::raw('SUM(ttl_airkotor) as tagihan_airkotor'),
        DB::raw('SUM(rea_airkotor) as realisasi_airkotor'),
        DB::raw('SUM(sel_airkotor) as selisih_airkotor'),
        DB::raw('SUM(ttl_lain) as tagihan_lain'),
        DB::raw('SUM(rea_lain) as realisasi_lain'),
        DB::raw('SUM(sel_lain) as selisih_lain'),
        DB::raw('SUM(ttl_tagihan) as tagihan_total'),
        DB::raw('SUM(rea_tagihan) as realisasi_total'),
        DB::raw('SUM(sel_tagihan) as selisih_total'),
        DB::raw('SUM(dis_tagihan) as diskon_total'),
        DB::raw('SUM(den_tagihan) as denda_total'),
        )
        ->get();
        $dataset = array();
        $dataset['tagihan_listrik'] = number_format($data[0]->tagihan_listrik);
        $dataset['realisasi_listrik'] = number_format($data[0]->realisasi_listrik);
        $dataset['selisih_listrik'] = number_format($data[0]->selisih_listrik);
        $dataset['diskon_listrik'] = number_format($data[0]->diskon_listrik);
        $dataset['denda_listrik'] = number_format($data[0]->denda_listrik);
        
        $dataset['tagihan_airbersih'] = number_format($data[0]->tagihan_airbersih);
        $dataset['realisasi_airbersih'] = number_format($data[0]->realisasi_airbersih);
        $dataset['selisih_airbersih'] = number_format($data[0]->selisih_airbersih);
        $dataset['diskon_airbersih'] = number_format($data[0]->diskon_airbersih);
        $dataset['denda_airbersih'] = number_format($data[0]->denda_airbersih);
        
        $dataset['tagihan_keamananipk'] = number_format($data[0]->tagihan_keamananipk);
        $dataset['realisasi_keamananipk'] = number_format($data[0]->realisasi_keamananipk);
        $dataset['selisih_keamananipk'] = number_format($data[0]->selisih_keamananipk);
        $dataset['diskon_keamananipk'] = number_format($data[0]->diskon_keamananipk);
        
        $dataset['tagihan_kebersihan'] = number_format($data[0]->tagihan_kebersihan);
        $dataset['realisasi_kebersihan'] = number_format($data[0]->realisasi_kebersihan);
        $dataset['selisih_kebersihan'] = number_format($data[0]->selisih_kebersihan);
        $dataset['diskon_kebersihan'] = number_format($data[0]->diskon_kebersihan);
        
        $dataset['tagihan_airkotor'] = number_format($data[0]->tagihan_airkotor);
        $dataset['realisasi_airkotor'] = number_format($data[0]->realisasi_airkotor);
        $dataset['selisih_airkotor'] = number_format($data[0]->selisih_airkotor);
        
        $dataset['tagihan_lain'] = number_format($data[0]->tagihan_lain);
        $dataset['realisasi_lain'] = number_format($data[0]->realisasi_lain);
        $dataset['selisih_lain'] = number_format($data[0]->selisih_lain);
        
        $dataset['tagihan_total'] = number_format($data[0]->tagihan_total);
        $dataset['realisasi_total'] = number_format($data[0]->realisasi_total);
        $dataset['selisih_total'] = number_format($data[0]->selisih_total);
        $dataset['diskon_total'] = number_format($data[0]->diskon_total);
        $dataset['denda_total'] = number_format($data[0]->denda_total);

        return response()->json(["result" => $dataset]);
    }
}
