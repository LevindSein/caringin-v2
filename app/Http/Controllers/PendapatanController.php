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
            ->editColumn('tgl_bayar', function ($data) {
                return date('d-m-Y',strtotime($data->tgl_bayar));
            })
            ->editColumn('byr_listrik', function ($data) {
                return number_format($data->byr_listrik);
            })
            ->editColumn('byr_airbersih', function ($data) {
                return number_format($data->byr_airbersih);
            })
            ->editColumn('byr_keamananipk', function ($data) {
                return number_format($data->byr_keamananipk);
            })
            ->editColumn('byr_kebersihan', function ($data) {
                return number_format($data->byr_kebersihan);
            })
            ->editColumn('byr_airkotor', function ($data) {
                return number_format($data->byr_airkotor);
            })
            ->editColumn('byr_lain', function ($data) {
                return number_format($data->byr_lain);
            })
            ->editColumn('ttl_tagihan', function ($data) {
                return number_format($data->ttl_tagihan);
            })
            ->editColumn('realisasi', function ($data) {
                return number_format($data->realisasi);
            })
            ->editColumn('sel_tagihan', function ($data) {
                return number_format($data->sel_tagihan);
            })
            ->make(true);
        }
        return view('pendapatan.index');
    }

    public function bulanan(Request $request){
        if($request->ajax())
        {
            $data = Pembayaran::select('bln_bayar')->groupBy('bln_bayar')->orderBy('bln_bayar','desc');
            return DataTables::of($data)
            ->addColumn('listrik', function($data){
                $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                ->select(DB::raw('SUM(byr_listrik) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('airbersih', function($data){
                $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                ->select(DB::raw('SUM(byr_airbersih) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('keamananipk', function($data){
                $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                ->select(DB::raw('SUM(byr_keamananipk) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('kebersihan', function($data){
                $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                ->select(DB::raw('SUM(byr_kebersihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('airkotor', function($data){
                $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                ->select(DB::raw('SUM(byr_airkotor) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('lain', function($data){
                $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                ->select(DB::raw('SUM(byr_lain) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('diskon', function($data){
                $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                ->select(DB::raw('SUM(diskon) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
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
            ->editColumn('bln_bayar', function($data){
                return IndoDate::bulanS($data->bln_bayar,' ');
            })
            ->make(true);
        }
    }

    public function tahunan(Request $request){
        if($request->ajax())
        {
            $data = Pembayaran::select('thn_bayar')->groupBy('thn_bayar')->orderBy('thn_bayar','desc');
            return DataTables::of($data)
            ->addColumn('listrik', function($data){
                $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                ->select(DB::raw('SUM(byr_listrik) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('airbersih', function($data){
                $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                ->select(DB::raw('SUM(byr_airbersih) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('keamananipk', function($data){
                $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                ->select(DB::raw('SUM(byr_keamananipk) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('kebersihan', function($data){
                $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                ->select(DB::raw('SUM(byr_kebersihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('airkotor', function($data){
                $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                ->select(DB::raw('SUM(byr_airkotor) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('lain', function($data){
                $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                ->select(DB::raw('SUM(byr_lain) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('diskon', function($data){
                $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                ->select(DB::raw('SUM(diskon) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
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
