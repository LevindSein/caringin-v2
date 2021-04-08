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
            ->make(true);
        }
        return view('datausaha.index');
    }

    public function tunggakan()
    {
        if(request()->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->where('sel_tagihan','>',0);
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
                return IndoDate::bulanS($data->bln_tagihan,' ');
            })
            ->make(true);
        }
    }
}
