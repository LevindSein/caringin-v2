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
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->orderBy('bln_tagihan','desc');
            return DataTables::of($data)
            ->addColumn('ttl_listrik', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_listrik) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('dis_listrik', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(dis_listrik) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('rea_listrik', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_listrik) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_listrik', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_listrik) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('ttl_airbersih', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_airbersih) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('dis_airbersih', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(dis_airbersih) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('rea_airbersih', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_airbersih) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_airbersih', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_airbersih) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('ttl_keamananipk', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_keamananipk) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('dis_keamananipk', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(dis_keamananipk) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('rea_keamananipk', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_keamananipk) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_keamananipk', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_keamananipk) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('ttl_kebersihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_kebersihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('dis_kebersihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(dis_kebersihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('rea_kebersihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_kebersihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_kebersihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_kebersihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('ttl_airkotor', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_airkotor) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('rea_airkotor', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_airkotor) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_airkotor', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_airkotor) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('ttl_lain', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_lain) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('rea_lain', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_lain) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_lain', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_lain) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
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
            ->addColumn('dis_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(dis_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
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
                return IndoDate::bulan($data->bln_tagihan,' ');
            })
            ->make(true);
        }
        return view('datausaha.index');
    }

    public function tunggakan(Request $request){
        if($request->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->orderBy('bln_tagihan','desc');
            return DataTables::of($data)
            ->addColumn('sel_listrik', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_listrik) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_airbersih', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_airbersih) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_keamananipk', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_keamananipk) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_kebersihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_kebersihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_airkotor', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_airkotor) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_lain', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_lain) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
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
                return IndoDate::bulan($data->bln_tagihan,' ');
            })
            ->make(true);
        }
    }

    public function bongkaran(Request $request){
        if($request->ajax()){
            $data = TempatUsaha::orderBy('kd_kontrol','asc');
            return DataTables::of($data)
            ->addColumn('dendasatu', function ($data) {
                $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_denda',1]])->first();
                if ($tagihan != NULL) return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                else 
                return '<span class="text-center"></span>';
            })
            ->addColumn('dendadua', function ($data) {
                $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_denda',2]])->first();
                if ($tagihan != NULL) return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                else 
                return '<span class="text-center"></span>';
            })
            ->addColumn('dendatiga', function ($data) {
                $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_denda',3]])->first();
                if ($tagihan != NULL) return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                else 
                return '<span class="text-center"></span>';
            })
            ->addColumn('dendaempat', function ($data) {
                $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_denda',4]])->first();
                if ($tagihan != NULL) return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                else 
                return '<span class="text-center"></span>';
            })
            ->addColumn('tunggakan', function ($data) {
                $tagihan = Tagihan::where('kd_kontrol',$data->kd_kontrol)
                ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('action', function ($data) {
                if($data->stt_bongkar === 0){
                    $button = '<button type="button" title="Unduh Peringatan" name="peringatan" id="'.$data->id.'" class="btn btn-sm btn-warning peringatan">Peringatan</button>';
                    return $button;
                }
                if($data->stt_bongkar === 1){
                    $button = '<button type="button" title="Bongkar" name="bongkar" id="'.$data->id.'" class="btn btn-sm btn-danger bongkar">Bongkar</button>';
                    return $button;
                }
                if($data->stt_bongkar === 2){
                    $button = '<button type="button" title="Selesai" name="selesai" id="'.$data->id.'" class="btn btn-sm btn-success selesai">Selesai</button>';
                    return $button;
                }
            })
            ->rawColumns(['dendasatu','dendadua','dendatiga','dendaempat','action'])
            ->make(true);
        }
    }

    public function penghapusan(Request $request){
        if($request->ajax()){
            $data = Penghapusan::orderBy('id','desc');
            return DataTables::of($data)
            ->editColumn('sel_listrik', function($data){
                return number_format($data->sel_listrik);
            })
            ->editColumn('sel_airbersih', function($data){
                return number_format($data->sel_airbersih);
            })
            ->editColumn('sel_airbersih', function($data){
                return number_format($data->sel_airbersih);
            })
            ->editColumn('sel_keamananipk', function($data){
                return number_format($data->sel_keamananipk);
            })
            ->editColumn('sel_kebersihan', function($data){
                return number_format($data->sel_kebersihan);
            })
            ->editColumn('sel_airkotor', function($data){
                return number_format($data->sel_airkotor);
            })
            ->editColumn('sel_lain', function($data){
                return number_format($data->sel_lain);
            })
            ->editColumn('sel_tagihan', function($data){
                return number_format($data->sel_tagihan);
            })
            ->editColumn('bln_tagihan', function($data){
                return IndoDate::bulan($data->bln_tagihan,' ');
            })
            ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
