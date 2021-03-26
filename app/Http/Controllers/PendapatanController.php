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
                $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" bayar="'.$data->tgl_bayar.'" class="harian btn btn-sm btn-primary">Show</button>';
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
                $button = '<button title="Show Details" name="show" id="'.$data->bln_bayar.'" nama="'.$data->bln_bayar.'" class="bulanan btn btn-sm btn-primary">Show</button>';
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
                $button = '<button title="Show Details" name="show" id="'.$data->thn_bayar.'" nama="'.$data->thn_bayar.'" class="tahunan btn btn-sm btn-primary">Show</button>';
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
    public function show($fas, $id)
    {
        if(request()->ajax()){
            if($fas == 'harian'){
                $data = Pembayaran::find($id);
            }

            if($fas == 'bulanan'){
                $tagihan = Pembayaran::where('bln_bayar',$id)
                ->select(
                    DB::raw('SUM(realisasi) as realisasi'),
                    DB::raw('SUM(diskon) as diskon'),
                    DB::raw('SUM(byr_listrik) as listrik'),
                    DB::raw('SUM(byr_denlistrik) as denlistrik'),
                    DB::raw('SUM(dis_listrik) as dislistrik'),
                    DB::raw('SUM(byr_airbersih) as airbersih'),
                    DB::raw('SUM(byr_denairbersih) as denairbersih'),
                    DB::raw('SUM(dis_airbersih) as disairbersih'),
                    DB::raw('SUM(byr_keamananipk) as keamananipk'),
                    DB::raw('SUM(dis_keamananipk) as diskeamananipk'),
                    DB::raw('SUM(byr_kebersihan) as kebersihan'),
                    DB::raw('SUM(dis_kebersihan) as diskebersihan'),
                    DB::raw('SUM(byr_airkotor) as airkotor'),
                    DB::raw('SUM(byr_lain) as lain'),
                )
                ->get();

                $data['realisasi'] = $tagihan[0]->realisasi;
                $data['diskon'] = $tagihan[0]->diskon;

                $data['byr_listrik'] = $tagihan[0]->listrik;
                $data['byr_denlistrik'] = $tagihan[0]->denlistrik;
                $data['dis_listrik'] = $tagihan[0]->dislistrik;
                
                $data['byr_airbersih'] = $tagihan[0]->airbersih;
                $data['byr_denairbersih'] = $tagihan[0]->denairbersih;
                $data['dis_airbersih'] = $tagihan[0]->disairbersih;
                
                $data['byr_keamananipk'] = $tagihan[0]->keamananipk;
                $data['dis_keamananipk'] = $tagihan[0]->diskeamananipk;
                
                $data['byr_kebersihan'] = $tagihan[0]->kebersihan;
                $data['dis_kebersihan'] = $tagihan[0]->diskebersihan;
                
                $data['byr_airkotor'] = $tagihan[0]->airkotor;
                
                $data['byr_lain'] = $tagihan[0]->lain;

                $data['bulan'] = IndoDate::bulan($id, " ");
            }

            if($fas == 'tahunan'){
                $tagihan = Pembayaran::where('thn_bayar',$id)
                ->select(
                    DB::raw('SUM(realisasi) as realisasi'),
                    DB::raw('SUM(diskon) as diskon'),
                    DB::raw('SUM(byr_listrik) as listrik'),
                    DB::raw('SUM(byr_denlistrik) as denlistrik'),
                    DB::raw('SUM(dis_listrik) as dislistrik'),
                    DB::raw('SUM(byr_airbersih) as airbersih'),
                    DB::raw('SUM(byr_denairbersih) as denairbersih'),
                    DB::raw('SUM(dis_airbersih) as disairbersih'),
                    DB::raw('SUM(byr_keamananipk) as keamananipk'),
                    DB::raw('SUM(dis_keamananipk) as diskeamananipk'),
                    DB::raw('SUM(byr_kebersihan) as kebersihan'),
                    DB::raw('SUM(dis_kebersihan) as diskebersihan'),
                    DB::raw('SUM(byr_airkotor) as airkotor'),
                    DB::raw('SUM(byr_lain) as lain'),
                )
                ->get();

                $data['realisasi'] = $tagihan[0]->realisasi;
                $data['diskon'] = $tagihan[0]->diskon;

                $data['byr_listrik'] = $tagihan[0]->listrik;
                $data['byr_denlistrik'] = $tagihan[0]->denlistrik;
                $data['dis_listrik'] = $tagihan[0]->dislistrik;
                
                $data['byr_airbersih'] = $tagihan[0]->airbersih;
                $data['byr_denairbersih'] = $tagihan[0]->denairbersih;
                $data['dis_airbersih'] = $tagihan[0]->disairbersih;
                
                $data['byr_keamananipk'] = $tagihan[0]->keamananipk;
                $data['dis_keamananipk'] = $tagihan[0]->diskeamananipk;
                
                $data['byr_kebersihan'] = $tagihan[0]->kebersihan;
                $data['dis_kebersihan'] = $tagihan[0]->diskebersihan;
                
                $data['byr_airkotor'] = $tagihan[0]->airkotor;
                
                $data['byr_lain'] = $tagihan[0]->lain;
            }

            return response()->json(['result' => $data]);
        }
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
