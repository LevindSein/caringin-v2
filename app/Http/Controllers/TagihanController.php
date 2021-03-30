<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;
use Validator;
use Exception;

use App\Models\Tagihan;
use App\Models\TempatUsaha;
use App\Models\AlatListrik;
use App\Models\AlatAir;
use App\Models\Penghapusan;
use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;

use App\Models\Pembayaran;
use App\Models\Neraca;

use App\Models\HariLibur;
use App\Models\Sinkronisasi;
use App\Models\IndoDate;
use App\Models\Terbilang;
use App\Models\Blok;

use Carbon\Carbon;
use App\Models\Carbonet;

class TagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware('tagihan');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $now = date("Y-m-d",strtotime(Carbon::now()));
        $check = date("Y-m-25",strtotime(Carbon::now()));

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

        if($request->ajax())
        {
            $data = Tagihan::where('bln_tagihan',Session::get('periodetagihan'));
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if($data->stt_publish === 0){
                        if($data->review === 0)
                            $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        else
                            $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#000000;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Publish" name="unpublish" id="'.$data->id.'" class="unpublish"><i class="fas fa-check-circle" style="color:#1cc88a;"></i></a>';
                    }
                    else{
                        if(Session::get('role') == 'master')
                            $button = '<button type="button" title="Cancel Publish" name="unpublish" id="'.$data->id.'" class="unpublish btn btn-sm btn-danger">Unpublish</button>';
                        else
                            $button = '<span style="color:#1cc88a;">Published</span>';
                    }
                    return $button;
                })
                ->editColumn('kd_kontrol', function ($data) {
                    $hasil = $data->kd_kontrol;
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->kd_kontrol === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2 || $warna == 3)
                            return '<span class="text-center" style="color:#4e73df;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('nama', function ($data) {
                    $hasil = $data->nama;
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->nama === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2 || $warna == 3)
                            return '<span class="text-center" style="color:#4e73df;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_listrik', function ($data) {
                    $hasil = number_format($data->ttl_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->ttl_listrik == 0 && $data->stt_listrik === NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totallistrik" id="'.$data->id.'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                    else if ($data->ttl_listrik == 0 && $data->stt_listrik !== NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totallistrik" id="'.$data->id.'"><span style="color:#172b4d;">0</span></a>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totallistrik" id="'.$data->id.'"><span style="color:#c4b71f;">'.$hasil.'</span></a>';
                        else if($warna == 3)
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totallistrik" id="'.$data->id.'"><span style="color:#e74a3b;">'.$hasil.'</span></a>';
                        else
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totallistrik" id="'.$data->id.'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                    }
                })
                ->editColumn('ttl_airbersih', function ($data) {
                    $hasil = number_format($data->ttl_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->ttl_airbersih == 0 && $data->stt_airbersih === NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalairbersih" id="'.$data->id.'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                    else if ($data->ttl_airbersih == 0 && $data->stt_airbersih !== NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalairbersih" id="'.$data->id.'"><span style="color:#172b4d;">0</span></a>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totalairbersih" id="'.$data->id.'"><span style="color:#c4b71f;">'.$hasil.'</span></a>';
                        else if($warna == 3)
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totalairbersih" id="'.$data->id.'"><span style="color:#e74a3b;">'.$hasil.'</span></a>';
                        else
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totalairbersih" id="'.$data->id.'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                    }
                })
                ->editColumn('ttl_keamananipk', function ($data) {
                    $hasil = number_format($data->ttl_keamananipk);
                    if ($data->ttl_keamananipk == 0 && $data->stt_keamananipk === NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalkeamananipk" id="'.$data->id.'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                    else if ($data->ttl_keamananipk == 0 && $data->stt_keamananipk !== NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalkeamananipk" id="'.$data->id.'"><span style="color:#172b4d;">0</span></a>';
                    else 
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalkeamananipk" id="'.$data->id.'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                })
                ->editColumn('ttl_kebersihan', function ($data) {
                    $hasil = number_format($data->ttl_kebersihan);
                    if ($data->ttl_kebersihan == 0 && $data->stt_kebersihan === NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalkebersihan" id="'.$data->id.'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                    else if ($data->ttl_kebersihan == 0 && $data->stt_kebersihan !== NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalkebersihan" id="'.$data->id.'"><span style="color:#172b4d;">0</span></a>';
                    else
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalkebersihan" id="'.$data->id.'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                })
                ->editColumn('ttl_airkotor', function ($data) {
                    $hasil = number_format($data->ttl_airkotor);
                    if ($data->ttl_airkotor == 0 && $data->stt_airkotor === NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalairkotor" id="'.$data->id.'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                    else if ($data->ttl_airkotor == 0 && $data->stt_airkotor !== NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalairkotor" id="'.$data->id.'"><span style="color:#172b4d;">0</span></a>';
                    else
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totalairkotor" id="'.$data->id.'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                })
                ->editColumn('ttl_lain', function ($data) {
                    $hasil = number_format($data->ttl_lain);
                    if ($data->ttl_lain == 0 && $data->stt_lain === NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totallain" id="'.$data->id.'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                    else if ($data->ttl_lain == 0 && $data->stt_lain !== NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totallain" id="'.$data->id.'"><span style="color:#172b4d;">0</span></a>';
                    else
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totallain" id="'.$data->id.'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                })
                ->editColumn('ttl_tagihan', function ($data) {
                    $hasil = number_format($data->ttl_tagihan);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_tagihan === NULL)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totaltagihan" id="'.$data->id.'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                    else if ($data->ttl_tagihan === 0)
                        return '<a href="javascript:void(0)" title="Click for Details!" class="totaltagihan" id="'.$data->id.'"><span style="color:#172b4d;">0</span></a>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totaltagihan" id="'.$data->id.'"><span style="color:#c4b71f;">'.$hasil.'</span></a>';
                        if($warna == 3)
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totaltagihan" id="'.$data->id.'"><span style="color:#e74a3b;">'.$hasil.'</span></a>';
                        else
                            return '<a href="javascript:void(0)" title="Click for Details!" class="totaltagihan" id="'.$data->id.'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show',
                    'action',
                    'kd_kontrol',
                    'nama',
                    'ttl_listrik',
                    'ttl_airbersih',
                    'ttl_keamananipk',
                    'ttl_kebersihan',
                    'ttl_airkotor',
                    'ttl_lain',
                    'ttl_tagihan',
                ])
                ->make(true);
        }

        return view('tagihan.index',[
            'periode' => IndoDate::bulan(Session::get('periodetagihan'),' '),
        ]);
    }
}
