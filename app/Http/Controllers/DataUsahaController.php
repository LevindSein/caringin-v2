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
                $button = '<button title="Show Details" name="show" id="'.$data->bln_tagihan.'" nama="'.$data->bln_tagihan.'" class="details btn btn-sm btn-primary">Show</button>';
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
                $button = '<button title="Show Details" name="show" id="'.$data->bln_tagihan.'" nama="'.$data->bln_tagihan.'" class="details btn btn-sm btn-primary">Show</button>';
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
                $button = '<button title="Show Details" name="show" id="'.$data->bln_tagihan.'" nama="'.$data->bln_tagihan.'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns(['show'])
            ->make(true);
        }
    }
}
