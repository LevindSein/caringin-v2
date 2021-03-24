<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Exception;

use App\Models\Pembayaran;
use App\Models\IndoDate;

class PendapatanController extends Controller
{
    public function __construct()
    {
        $this->middleware('pendapatan');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request){
        if($request->ajax())
        {
            $data = Pembayaran::orderBy('tgl_bayar','desc');
            return DataTables::of($data)
            ->editColumn('ttl_tagihan', function ($data) {
                return number_format($data->ttl_tagihan);
            })
            ->editColumn('realisasi', function ($data) {
                return number_format($data->realisasi);
            })
            ->editColumn('sel_tagihan', function ($data) {
                return number_format($data->sel_tagihan);
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns(['show'])
            ->make(true);
        }
        return view('laporan.pendapatan.index');
    }

    public function bulanan(Request $request){
        if($request->ajax())
        {
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
            ->rawColumns(['show'])
            ->make(true);
        }
    }

    public function tahunan(Request $request){
        if($request->ajax())
        {
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
            ->rawColumns(['show'])
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
