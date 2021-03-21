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
        
        if(Session::get('role') == 'admin')
            $wherein = Session::get('otoritas')->otoritas;

        // Notification
        $notif = 0;
        if(Session::get('role') == 'admin'){
            $wherein = Session::get('otoritas')->otoritas;
            $notif = Tagihan::where([['bln_tagihan','>=',$periode],['review',0]])->whereIn('blok',$wherein)->count();
        }
        else{
            $notif = Tagihan::where([['bln_tagihan','>=',$periode],['review',0]])->count();
        }

        if($notif > 0){
            $notif = 1;
        }

        if($request->ajax())
        {
            if(Session::get('role') == 'admin'){
                $wherein = Session::get('otoritas')->otoritas;
                $data = Tagihan::where('bln_tagihan',$periode)->whereIn('blok',$wherein);
            }
            else{
                $data = Tagihan::where('bln_tagihan',$periode);
            }
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
                ->editColumn('daya_listrik', function ($data) {
                    $hasil = number_format($data->daya_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->daya_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('awal_listrik', function ($data) {
                    $hasil = number_format($data->awal_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->awal_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_listrik', function ($data) {
                    $hasil = number_format($data->akhir_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->akhir_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_listrik', function ($data) {
                    $hasil = number_format($data->pakai_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->pakai_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_listrik', function ($data) {
                    $hasil = number_format($data->ttl_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->ttl_listrik == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="color:#f6c23e;font-size:13px;" class="listrik-hover;"><b>'.$hasil.'</b></span></a>';
                        else if($warna == 3)
                            return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="color:#e74a3b;font-size:13px;background-color:rgba(255, 169, 189, 0.2);"><b>'.$hasil.'</b></span></a>';
                        else
                            return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="font-size:13px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                    }
                })
                ->editColumn('awal_airbersih', function ($data) {
                    $hasil = number_format($data->awal_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->awal_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_airbersih', function ($data) {
                    $hasil = number_format($data->akhir_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->akhir_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_airbersih', function ($data) {
                    $hasil = number_format($data->pakai_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->pakai_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_airbersih', function ($data) {
                    $hasil = number_format($data->ttl_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->ttl_airbersih == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="color:#f6c23e;font-size:13px;" class="listrik-hover;"><b>'.$hasil.'</b></span></a>';
                        else if($warna == 3)
                            return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="color:#e74a3b;font-size:13px;background-color:rgba(255, 169, 189, 0.2);"><b>'.$hasil.'</b></span></a>';
                        else
                            return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="font-size:13px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                    }
                })
                ->editColumn('ttl_keamananipk', function ($data) {
                    $hasil = number_format($data->ttl_keamananipk);
                    if ($data->ttl_keamananipk == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else 
                        return '<a href="javascript:void(0)" class="totalkeamananipk" id="'.$data->id.'"><span style="font-size:13px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_kebersihan', function ($data) {
                    $hasil = number_format($data->ttl_kebersihan);
                    if ($data->ttl_kebersihan == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return '<a href="javascript:void(0)" class="totalkebersihan" id="'.$data->id.'"><span style="font-size:13px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_airkotor', function ($data) {
                    $hasil = number_format($data->ttl_airkotor);
                    if ($data->ttl_airkotor == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return '<a href="javascript:void(0)" class="totalairkotor" id="'.$data->id.'"><span style="font-size:13px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_lain', function ($data) {
                    $hasil = number_format($data->ttl_lain);
                    if ($data->ttl_lain == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return '<a href="javascript:void(0)" class="totallain" id="'.$data->id.'"><span style="font-size:13px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_tagihan', function ($data) {
                    $hasil = number_format($data->ttl_tagihan);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_tagihan === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2 || $warna == 3)
                            return '<a href="javascript:void(0)" class="totaltagihan" id="'.$data->id.'"><span style="color:#4e73df;font-size:13px;"><b>'.$hasil.'</b></span></a>';
                        else
                            return '<a href="javascript:void(0)" class="totaltagihan" id="'.$data->id.'"><span style="font-size:13px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                    }
                })
                ->rawColumns([
                    'action',
                    'los',
                    'kd_kontrol',
                    'nama',
                    'daya_listrik',
                    'awal_listrik',
                    'akhir_listrik',
                    'pakai_listrik',
                    'ttl_listrik',
                    'awal_airbersih',
                    'akhir_airbersih',
                    'pakai_airbersih',
                    'ttl_airbersih',
                    'ttl_keamananipk',
                    'ttl_kebersihan',
                    'ttl_airkotor',
                    'ttl_lain',
                    'ttl_tagihan',
                ])
                ->make(true);
        }

        if(Session::get('role') == 'admin'){
            return view('tagihan.index',[
                'periode'       => IndoDate::bulan($periode,' '),
                'tahun'         => Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','asc')->get(),
                'blok'          => Blok::select('nama')->whereIn('nama',$wherein)->orderBy('nama')->get(),
                'listrik_badge' => Tagihan::where([['tagihan.stt_listrik',0],['tempat_usaha.trf_listrik',1]])
                                    ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                                    ->whereIn('tagihan.blok',$wherein)
                                    ->count(),
                'air_badge'     => Tagihan::where([['tagihan.stt_airbersih',0],['tempat_usaha.trf_airbersih',1]])
                                    ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
                                    ->whereIn('tagihan.blok',$wherein)
                                    ->count(),
                'notif'         => $notif,
            ]);
        }
        else{
            return view('tagihan.index',[
                'periode'       => IndoDate::bulan($periode,' '),
                'tahun'         => Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','asc')->get(),
                'blok'          => Blok::select('nama')->orderBy('nama')->get(),
                'listrik_badge' => Tagihan::where('stt_listrik',0)
                                    ->count(),
                'air_badge'     => Tagihan::where('tagihan.stt_airbersih',0)
                                    ->count(),
                'notif'         => $notif,
            ]);
        }
    }

    public function periode(Request $request){
        $periode = $request->tahun."-".$request->bulan;
        Session::put('bln_periode',$request->bulan);
        Session::put('thn_periode',$request->tahun);
        Session::put('periode', $periode);
        
        if(Session::get('role') == 'admin')
            $wherein = Session::get('otoritas')->otoritas;

        // Notification
        $notif = 0;
        if(Session::get('role') == 'admin'){
            $wherein = Session::get('otoritas')->otoritas;
            $notif = Tagihan::where([['bln_tagihan','>=',$periode],['review',0]])->whereIn('blok',$wherein)->count();
        }
        else{
            $notif = Tagihan::where([['bln_tagihan','>=',$periode],['review',0]])->count();
        }

        if($notif > 0){
            $notif = 1;
        }
        
        if($request->ajax())
        {
            if(Session::get('role') == 'admin'){
                $wherein = Session::get('otoritas')->otoritas;
                $data = Tagihan::where('bln_tagihan',Session::get('periode'))->whereIn('blok',$wherein);;
            }
            else{
                $data = Tagihan::where('bln_tagihan',Session::get('periode'));
            }
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
                            $button = '<span style="color:#1cc88a">Published</span>';
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
                ->editColumn('daya_listrik', function ($data) {
                    $hasil = number_format($data->daya_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->daya_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('awal_listrik', function ($data) {
                    $hasil = number_format($data->awal_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->awal_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_listrik', function ($data) {
                    $hasil = number_format($data->akhir_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->akhir_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_listrik', function ($data) {
                    $hasil = number_format($data->pakai_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->pakai_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_listrik', function ($data) {
                    $hasil = number_format($data->ttl_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->ttl_listrik == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('awal_airbersih', function ($data) {
                    $hasil = number_format($data->awal_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->awal_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_airbersih', function ($data) {
                    $hasil = number_format($data->akhir_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->akhir_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_airbersih', function ($data) {
                    $hasil = number_format($data->pakai_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->pakai_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_airbersih', function ($data) {
                    $hasil = number_format($data->ttl_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->ttl_airbersih == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_keamananipk', function ($data) {
                    $hasil = number_format($data->ttl_keamananipk);
                    if ($data->ttl_keamananipk == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else 
                        return $hasil;
                })
                ->editColumn('ttl_kebersihan', function ($data) {
                    $hasil = number_format($data->ttl_kebersihan);
                    if ($data->ttl_kebersihan == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return $hasil;
                })
                ->editColumn('ttl_airkotor', function ($data) {
                    $hasil = number_format($data->ttl_airkotor);
                    if ($data->ttl_airkotor == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return $hasil;
                })
                ->editColumn('ttl_lain', function ($data) {
                    $hasil = number_format($data->ttl_lain);
                    if ($data->ttl_lain == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return $hasil;
                })
                ->editColumn('ttl_tagihan', function ($data) {
                    $hasil = number_format($data->ttl_tagihan);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if ($data->ttl_tagihan === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2 || $warna == 3)
                            return '<span class="text-center" style="color:#4e73df;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->rawColumns([
                    'action',
                    'los',
                    'kd_kontrol',
                    'nama',
                    'daya_listrik',
                    'awal_listrik',
                    'akhir_listrik',
                    'pakai_listrik',
                    'ttl_listrik',
                    'awal_airbersih',
                    'akhir_airbersih',
                    'pakai_airbersih',
                    'ttl_airbersih',
                    'ttl_keamananipk',
                    'ttl_kebersihan',
                    'ttl_airkotor',
                    'ttl_lain',
                    'ttl_tagihan',
                ])
                ->make(true);
        }

        if(Session::get('role') == 'admin'){
            return view('tagihan.periode',[
                'periode'       => IndoDate::bulan($periode,' '),
                'tahun'         => Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','asc')->get()
            ]);
        }
        else{
            return view('tagihan.periode',[
                'periode'       => IndoDate::bulan($periode,' '),
                'tahun'         => Tagihan::select('thn_tagihan')->groupBy('thn_tagihan')->orderBy('thn_tagihan','asc')->get()
            ]);
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
        if(request()->ajax())
        {
            $data = Tagihan::findOrFail($id);
            
            $tempat = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->first();
            $data['listrik'] = $tempat->trf_listrik;
            $data['airbersih'] = $tempat->trf_airbersih;
            $data['keamananipk'] = $tempat->trf_keamananipk;
            $data['kebersihan'] = $tempat->trf_kebersihan;
            $data['airkotor'] = $tempat->trf_airkotor;
            $data['lain'] = $tempat->trf_lain;

            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try{
            $id = $request->hidden_id;

            $tagihan = Tagihan::find($id);
            $tagihan->nama = $request->pengguna;
            $tagihan->via_tambah = Session::get('username');
            $tagihan->save();
            
            if($request->stt_listrik == 'ok'){
                $daya = $request->dayaListrik;
                $daya = explode(',',$daya);
                $daya = implode("",$daya);

                $awal = $request->awalListrik;
                $awal = explode(',',$awal);
                $awal = implode("",$awal);

                $akhir = $request->akhirListrik;
                $akhir = explode(',',$akhir);
                $akhir = implode("",$akhir);

                $tempat = TempatUsaha::where('kd_kontrol',$request->kontrol)->first();
                if($tempat != NULL){
                    //Update Meteran
                    $tempat->daya = $daya;
                    $tempat->save();

                    $meter = AlatListrik::find($tempat->id_meteran_listrik);
                    if($meter != NULL){
                        $meter->akhir = $akhir;
                        $meter->daya  = $daya;
                        $meter->save();
                    }
                }

                //Update Tagihan
                Tagihan::listrik($awal,$akhir,$daya,$id);
            }

            if($request->stt_airbersih == 'ok'){
                $awal = $request->awalAir;
                $awal = explode(',',$awal);
                $awal = implode("",$awal);

                $akhir = $request->akhirAir;
                $akhir = explode(',',$akhir);
                $akhir = implode("",$akhir);

                $tempat = TempatUsaha::where('kd_kontrol',$request->kontrol)->first();
                if($tempat != NULL){
                    $meter = AlatAir::find($tempat->id_meteran_air);
                    if($meter != NULL){
                        $meter->akhir = $akhir;
                        $meter->save();
                    }
                }

                //Update Tagihan
                Tagihan::airbersih($awal,$akhir,$id);
            }

            if($request->stt_keamananipk == 'ok'){
                $tarif = $request->keamananipk;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $diskon = $request->dis_keamananipk;
                $diskon = explode(',',$diskon);
                $diskon = implode("",$diskon);

                if($diskon > $tarif){
                    $diskon = $tarif;
                }
                $tagihan = Tagihan::find($id);
                $tagihan->sub_keamananipk = $tarif;
                $tagihan->dis_keamananipk = $diskon;
                $tagihan->ttl_keamananipk = $tarif - $diskon;
                $tagihan->sel_keamananipk = $tagihan->ttl_keamananipk - $tagihan->rea_keamananipk;
                $tagihan->stt_keamananipk = 1;

                //Diskon Tempat
                $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
                if($tempat != NULL){
                    $tempat->dis_keamananipk = $diskon;
                    $tempat->save();
                }
                $tarif = TarifKeamananIpk::find($tempat->trf_keamananipk);
                $tagihan->ttl_keamanan = ($tarif->prs_keamanan / 100) * $tagihan->ttl_keamananipk;
                $tagihan->ttl_ipk = ($tarif->prs_ipk / 100) * $tagihan->ttl_keamananipk;

                $tagihan->save();
            }

            if($request->stt_kebersihan == 'ok'){
                $tarif = $request->kebersihan;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $diskon = $request->dis_kebersihan;
                $diskon = explode(',',$diskon);
                $diskon = implode("",$diskon);

                if($diskon > $tarif){
                    $diskon = $tarif;
                }
                $tagihan = Tagihan::find($id);
                $tagihan->sub_kebersihan = $tarif;
                $tagihan->dis_kebersihan = $diskon;
                $tagihan->ttl_kebersihan = $tarif - $diskon;
                $tagihan->sel_kebersihan = $tagihan->ttl_kebersihan - $tagihan->rea_kebersihan;
                $tagihan->stt_kebersihan = 1;

                //Diskon Tempat
                $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
                if($tempat != NULL){
                    $tempat->dis_kebersihan = $diskon;
                    $tempat->save();
                }

                $tagihan->save();
            }

            if($request->stt_airkotor == 'ok'){
                $tarif = $request->airkotor;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $tagihan = Tagihan::find($id);
                $tagihan->ttl_airkotor = $tarif;
                $tagihan->sel_airkotor = $tagihan->ttl_airkotor - $tagihan->rea_airkotor;
                $tagihan->stt_airkotor = 1;
                $tagihan->save();
            }
            
            if($request->stt_lain == 'ok'){
                $tarif = $request->lain;
                $tarif = explode(',',$tarif);
                $tarif = implode("",$tarif);

                $tagihan = Tagihan::find($id);
                $tagihan->ttl_lain = $tarif;
                $tagihan->sel_lain = $tagihan->ttl_lain - $tagihan->rea_lain;
                $tagihan->stt_lain = 1;
                $tagihan->save();
            }

            $tagihan = Tagihan::find($id);
            $tagihan->review   = 2;
            $tagihan->reviewer = "Checked by ".Session::get('username');
            $tagihan->ket      = $request->pesan;
            $tagihan->save();

            Tagihan::totalTagihan($id);

            if(empty($request->tambahdenda) == FALSE){
                Tagihan::tambahDenda($id);
            }

            if(empty($request->hapusdenda) == FALSE){
                Tagihan::hapusDenda($id);
            }

            //Notification
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
            $notif = 0;
            if(Session::get('role') == 'admin'){
                $wherein = Session::get('otoritas')->otoritas;
                $notif = Tagihan::where([['bln_tagihan','>=',$periode],['review',0]])->whereIn('blok',$wherein)->count();
            }
            else{
                $notif = Tagihan::where([['bln_tagihan','>=',$periode],['review',0]])->count();
            }

            if($notif > 0){
                $notif = 1;
            }

            $data = array();
            $data['success'] = 'Data Berhasil Diedit';
            $data['notif']   = $notif; 

            $tagihan = Tagihan::find($id);
            $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
            if($tempat != NULL){
                $tagihan->jml_alamat = $tempat->jml_alamat;
                $tagihan->no_alamat  = $tempat->no_alamat;
                $tagihan->lok_tempat = $tempat->lok_tempat;
                $tagihan->save();
            }

            return response()->json(['result' => $data]);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Diedit.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyEdit(Request $request, $id){
        if($request->ajax()){
            $data = Tagihan::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function destroy(Request $request,$id)
    {
        if($request->ajax()){
            try{
                $data = Tagihan::findOrFail($id);
                
                $hapus = Penghapusan::find($data->id);
                if($hapus == NULL){
                    $hapus       = new Penghapusan;
                }
                $hapus->id   = $data->id;
                $hapus->nama = $data->nama;
                $hapus->blok = $data->blok;
                $hapus->kd_kontrol = $data->kd_kontrol;
                $hapus->bln_pakai = $data->bln_pakai;
                $hapus->tgl_tagihan = $data->tgl_tagihan;
                $hapus->bln_tagihan = $data->bln_tagihan;
                $hapus->thn_tagihan = $data->thn_tagihan;
                $hapus->tgl_expired = $data->tgl_expired;
                $hapus->stt_lunas = $data->stt_lunas;
                $hapus->stt_bayar = $data->stt_bayar;
                $hapus->ket = $data->ket;
                $hapus->via_tambah = $data->via_tambah;
                $hapus->via_hapus = Session::get('username');
                $hapus->stt_denda = $data->stt_denda;

                if(empty($request->checkListrik) == FALSE){
                    $hapus->daya_listrik  = $data->daya_listrik;
                    $data->daya_listrik   = NULL;
                    
                    $hapus->awal_listrik  = $data->awal_listrik;
                    $data->awal_listrik   = NULL;

                    $hapus->akhir_listrik = $data->akhir_listrik;
                    $data->akhir_listrik  = NULL;

                    $hapus->pakai_listrik = $data->pakai_listrik;
                    $data->pakai_listrik  = NULL;

                    $hapus->byr_listrik   = $data->byr_listrik;
                    $data->byr_listrik    = NULL;

                    $hapus->rekmin_listrik = $data->rekmin_listrik;
                    $data->rekmin_listrik  = NULL;

                    $hapus->blok1_listrik  = $data->blok1_listrik;
                    $data->blok1_listrik   = NULL;

                    $hapus->blok2_listrik  = $data->blok2_listrik;
                    $data->blok2_listrik   = NULL;

                    $hapus->beban_listrik = $data->beban_listrik;
                    $data->beban_listrik  = NULL;

                    $hapus->bpju_listrik  = $data->bpju_listrik;
                    $data->bpju_listrik   = NULL;

                    $hapus->sub_listrik   = $data->sub_listrik;
                    $data->sub_listrik    = 0;

                    $hapus->dis_listrik   = $data->dis_listrik;
                    $data->dis_listrik    = 0;

                    $hapus->ttl_listrik   = $data->ttl_listrik;
                    $data->ttl_listrik    = 0;

                    $hapus->rea_listrik   = $data->rea_listrik;
                    $data->rea_listrik    = 0;

                    $hapus->sel_listrik   = $data->sel_listrik;
                    $data->sel_listrik    = 0;

                    $hapus->den_listrik   = $data->den_listrik;
                    $data->den_listrik    = 0;

                    $hapus->stt_listrik   = $data->stt_listrik;
                    $data->stt_listrik    = NULL;
                }

                if(empty($request->checkAirBersih) == FALSE){
                    $hapus->awal_airbersih = $data->awal_airbersih;
                    $data->awal_airbersih = NULL;

                    $hapus->akhir_airbersih = $data->akhir_airbersih;
                    $data->akhir_airbersih = NULL;

                    $hapus->pakai_airbersih = $data->pakai_airbersih;
                    $data->pakai_airbersih = NULL;

                    $hapus->byr_airbersih = $data->byr_airbersih;
                    $data->byr_airbersih = NULL;

                    $hapus->pemeliharaan_airbersih = $data->pemeliharaan_airbersih;
                    $data->pemeliharaan_airbersih = NULL;

                    $hapus->beban_airbersih = $data->beban_airbersih;
                    $data->beban_airbersih = NULL;

                    $hapus->arkot_airbersih = $data->arkot_airbersih;
                    $data->arkot_airbersih = NULL;

                    $hapus->sub_airbersih = $data->sub_airbersih;
                    $data->sub_airbersih = 0;

                    $hapus->dis_airbersih = $data->dis_airbersih;
                    $data->dis_airbersih = 0;

                    $hapus->ttl_airbersih = $data->ttl_airbersih;
                    $data->ttl_airbersih  = 0;

                    $hapus->rea_airbersih = $data->rea_airbersih;
                    $data->rea_airbersih  = 0;

                    $hapus->sel_airbersih = $data->sel_airbersih;
                    $data->sel_airbersih  = 0;

                    $hapus->stt_airbersih = $data->stt_airbersih;
                    $data->stt_airbersih  = NULL;
                }
                if(empty($request->checkKeamananIpk) == FALSE){
                    $hapus->sub_keamananipk = $data->sub_keamananipk;
                    $data->sub_keamananipk = 0;

                    $hapus->dis_keamananipk = $data->dis_keamananipk;
                    $data->dis_keamananipk = 0;

                    $hapus->ttl_keamananipk = $data->ttl_keamananipk;
                    $data->ttl_keamananipk  = 0;

                    $hapus->ttl_keamanan = $data->ttl_keamanan;
                    $data->ttl_keamanan  = 0;

                    $hapus->ttl_ipk = $data->ttl_ipk;
                    $data->ttl_ipk  = 0;

                    $hapus->rea_keamananipk = $data->rea_keamananipk;
                    $data->rea_keamananipk  = 0;

                    $hapus->sel_keamananipk = $data->sel_keamananipk;
                    $data->sel_keamananipk  = 0;

                    $hapus->stt_keamananipk = $data->stt_keamananipk;
                    $data->stt_keamananipk  = NULL;
                }
                if(empty($request->checkKebersihan) == FALSE){
                    $hapus->sub_kebersihan = $data->sub_kebersihan;
                    $data->sub_kebersihan = 0;

                    $hapus->dis_kebersihan = $data->dis_kebersihan;
                    $data->dis_kebersihan = 0;

                    $hapus->ttl_kebersihan = $data->ttl_kebersihan;
                    $data->ttl_kebersihan  = 0;

                    $hapus->rea_kebersihan = $data->rea_kebersihan;
                    $data->rea_kebersihan  = 0;

                    $hapus->sel_kebersihan = $data->sel_kebersihan;
                    $data->sel_kebersihan  = 0;

                    $hapus->stt_kebersihan = $data->stt_kebersihan;
                    $data->stt_kebersihan  = NULL;
                }
                if(empty($request->checkAirKotor) == FALSE){
                    $hapus->ttl_airkotor = $data->ttl_airkotor;
                    $data->ttl_airkotor  = 0;

                    $hapus->rea_airkotor = $data->rea_airkotor;
                    $data->rea_airkotor  = 0;

                    $hapus->sel_airkotor = $data->sel_airkotor;
                    $data->sel_airkotor  = 0;

                    $hapus->stt_airkotor = $data->stt_airkotor;
                    $data->stt_airkotor  = NULL;
                }
                if(empty($request->checkLain) == FALSE){
                    $hapus->ttl_lain = $data->ttl_lain;
                    $data->ttl_lain  = 0;

                    $hapus->rea_lain = $data->rea_lain;
                    $data->rea_lain  = 0;

                    $hapus->sel_lain = $data->sel_lain;
                    $data->sel_lain  = 0;

                    $hapus->stt_lain = $data->stt_lain;
                    $data->stt_lain  = NULL;
                }
                $hapus->save();
                $data->save();

                Tagihan::totalTagihan($id);
                Penghapusan::totalTagihan($id);

                $data = Tagihan::findOrFail($id);
                if($data->ttl_tagihan == 0)
                    $data->delete();
            }
            catch(\Exception $e){
                return response()->json(['status' => 'Data gagal dihapus.']);
            }
            return response()->json(['status' => 'Data telah dihapus.']);
        }
    }

    public function print(){
        $blok = Blok::select('nama')->orderBy('nama')->get();
        $dataListrik = array();
        $dataAir = array();
        $i = 0;
        $j = 0;
        foreach($blok as $b){
            $tempatListrik = TempatUsaha::where([['blok',$b->nama],['trf_listrik',1]])->count();
            if($tempatListrik != 0){
                $dataListrik[$i][0] = $b->nama;
                $dataListrik[$i][1] = TempatUsaha::where([['tempat_usaha.blok', $b->nama],['trf_listrik',1]])
                ->leftJoin('user as pengguna','tempat_usaha.id_pengguna','=','pengguna.id')
                ->leftJoin('meteran_listrik','tempat_usaha.id_meteran_listrik','=','meteran_listrik.id')
                ->select(
                    'pengguna.nama as nama',
                    'tempat_usaha.kd_kontrol as kontrol',
                    'tempat_usaha.lok_tempat as lokasi',
                    'meteran_listrik.daya  as daya',
                    'meteran_listrik.nomor as nomor',
                    'meteran_listrik.akhir as lalu')
                ->orderBy('tempat_usaha.kd_kontrol')
                ->get();
                $i++;
            }

            $tempatAir = TempatUsaha::where([['blok',$b->nama],['trf_airbersih',1]])->count();
            if($tempatAir != 0){
                $dataAir[$j][0] = $b->nama;
                $dataAir[$j][1] = TempatUsaha::where([['tempat_usaha.blok', $b->nama],['trf_airbersih',1]])
                ->leftJoin('user as pengguna','tempat_usaha.id_pengguna','=','pengguna.id')
                ->leftJoin('meteran_air','tempat_usaha.id_meteran_air','=','meteran_air.id')
                ->select(
                    'pengguna.nama as nama',
                    'tempat_usaha.kd_kontrol as kontrol',
                    'tempat_usaha.lok_tempat as lokasi',
                    'meteran_air.nomor as nomor',
                    'meteran_air.akhir as lalu')
                ->orderBy('tempat_usaha.kd_kontrol')
                ->get();
                $j++;
            }
        }
        $bulan = IndoDate::bulan(date("Y-m",strtotime(Carbon::now()))," ");
        $dataset = [$dataListrik,$dataAir];
        return view('tagihan.print',[
            'dataset'=> $dataset,
            'bulan'  => $bulan
        ]);
    }

    public function listrik(Request $request){
        $blok    = $request->tagihan_blok;

        if(Session::get('role') == 'admin'){
            if (!in_array($blok, Session::get('otoritas')->otoritas)) {
                return redirect()->route('tagihan.index');
            }
        }

        $tagihan = Tagihan::where([['stt_listrik',0],['stt_publish',0],['blok',$blok]])->orderBy('kd_kontrol','asc')->first();

        if($tagihan == NULL){
            return redirect()->route('tagihan.index');
        }
        else{
            $suggest = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_listrik',1]])->orderBy('id','desc')->limit(3)->get();
            
            $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
            if($tempat != NULL){
                $meter = AlatListrik::find($tempat->id_meteran_listrik);
                if($meter != NULL)
                    $awal = $meter->akhir;
                else
                    $awal = $tagihan->awal_listrik;
            }
            else{
                $awal = $tagihan->awal_listrik;
            }

            if($suggest != NULL){
                $saran = 0;
                foreach($suggest as $sug){
                    $saran = $saran + $sug->pakai_listrik;
                }
                $suggest = round($saran / 3);
                $suggest = $suggest + $awal;
            }
            else{
                $suggest = $awal;
            }

            $tagihan['awal_listrik'] = $awal;

            $ket = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
            if($ket != NULL){
                $ket = $ket;
            }
            else{
                $ket = '';
            }

            return view('tagihan.listrik',['dataset' => $tagihan, 'suggest' => $suggest, 'ket' => $ket,'blok' => $blok]);
        }
    }

    public function listrikUpdate(Request $request){
        $blok = $request->hidden_blok;

        $daya = explode(',',$request->daya);
        $daya = implode('',$daya);
        
        $awal = explode(',',$request->awal);
        $awal = implode('',$awal);
        
        $akhir = explode(',',$request->akhir);
        $akhir = implode('',$akhir);
        
        Tagihan::listrik($awal, $akhir, $daya, $request->hidden_id);

        $tempat = TempatUsaha::where('kd_kontrol',$request->kd_kontrol)->first();
        if($tempat != NULL){
            $meter = AlatListrik::find($tempat->id_meteran_listrik);
            if($meter != NULL){
                $meter->akhir = $akhir;
                $meter->daya  = $daya;
                $meter->save();
            }
        }

        $tagihan = Tagihan::find($request->hidden_id);
        $tagihan->ket = $request->laporan;
        $tagihan->save();

        $nama = Tagihan::find($request->hidden_id);
        $nama->nama = $request->nama;
        $nama->save();

        $this->total($request->hidden_id);

        return redirect()->route('listrik',['tagihan_blok'=>$blok]);
    }
    
    public function airbersih(Request $request){
        $blok    = $request->tagihan_blok;

        if(Session::get('role') == 'admin'){
            if (!in_array($blok, Session::get('otoritas')->otoritas)) {
                return redirect()->route('tagihan.index');
            }
        }

        $tagihan = Tagihan::where([['stt_airbersih',0],['stt_publish',0],['blok',$blok]])->orderBy('kd_kontrol','asc')->first();

        if($tagihan == NULL){
            return redirect()->route('tagihan.index');
        }
        else{
            $suggest = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_airbersih',1]])->orderBy('id','desc')->limit(3)->get();
            
            $tempat = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
            if($tempat != NULL){
                $meter = AlatAir::find($tempat->id_meteran_air);
                if($meter != NULL)
                    $awal = $meter->akhir;
                else
                    $awal = $tagihan->awal_airbersih;
            }
            else{
                $awal = $tagihan->awal_airbersih;
            }

            if($suggest != NULL){
                $saran = 0;
                foreach($suggest as $sug){
                    $saran = $saran + $sug->pakai_airbersih;
                }
                $suggest = round($saran / 3);
                $suggest = $suggest + $awal;
            }
            else{
                $suggest = $awal;
            }

            $tagihan['awal_airbersih'] = $awal;
            
            $ket = TempatUsaha::where('kd_kontrol',$tagihan->kd_kontrol)->first();
            if($ket != NULL){
                $ket = $ket;
            }
            else{
                $ket = '';
            }

            return view('tagihan.airbersih',['dataset' => $tagihan, 'suggest' => $suggest, 'ket' => $ket,'blok' => $blok]);
        }
    }

    public function airbersihUpdate(Request $request){
        $blok = $request->hidden_blok;

        $awal = explode(',',$request->awal);
        $awal = implode('',$awal);
        
        $akhir = explode(',',$request->akhir);
        $akhir = implode('',$akhir);
        
        Tagihan::airbersih($awal, $akhir, $request->hidden_id);

        $tempat = TempatUsaha::where('kd_kontrol',$request->kd_kontrol)->first();
        if($tempat != NULL){
            $meter = AlatAir::find($tempat->id_meteran_air);
            if($meter != NULL){
                $meter->akhir = $akhir;
                $meter->save();
            }
        }

        $tagihan = Tagihan::find($request->hidden_id);
        $tagihan->ket = $request->laporan;
        $tagihan->save();

        $nama = Tagihan::find($request->hidden_id);
        $nama->nama = $request->nama;
        $nama->save();

        $this->total($request->hidden_id);

        return redirect()->route('airbersih',['tagihan_blok'=>$blok]);
    }

    public function total($id){
        $tagihan = Tagihan::find($id);
        //Subtotal
        $subtotal = 
                $tagihan->sub_listrik     + 
                $tagihan->sub_airbersih   + 
                $tagihan->sub_keamananipk + 
                $tagihan->sub_kebersihan  + 
                $tagihan->ttl_airkotor    + 
                $tagihan->ttl_lain;
        $tagihan->sub_tagihan = $subtotal;

        //Diskon
        $diskon = 
            $tagihan->dis_listrik     + 
            $tagihan->dis_airbersih   + 
            $tagihan->dis_keamananipk + 
            $tagihan->dis_kebersihan;
        $tagihan->dis_tagihan = $diskon;

        //Denda
        $tagihan->den_tagihan = $tagihan->den_listrik + $tagihan->den_airbersih;

        //TOTAL
        $total = 
            $tagihan->ttl_listrik     + 
            $tagihan->ttl_airbersih   + 
            $tagihan->ttl_keamananipk + 
            $tagihan->ttl_kebersihan  + 
            $tagihan->ttl_airkotor    + 
            $tagihan->ttl_lain;
        $tagihan->ttl_tagihan = $total;

        //Realisasi
        $realisasi = 
                $tagihan->rea_listrik     + 
                $tagihan->rea_airbersih   + 
                $tagihan->rea_keamananipk + 
                $tagihan->rea_kebersihan  + 
                $tagihan->rea_airkotor    + 
                $tagihan->rea_lain;
        $tagihan->rea_tagihan = $realisasi;

        //Selisih
        $selisih =
                $tagihan->sel_listrik     + 
                $tagihan->sel_airbersih   + 
                $tagihan->sel_keamananipk + 
                $tagihan->sel_kebersihan  + 
                $tagihan->sel_airkotor    + 
                $tagihan->sel_lain;
        $tagihan->sel_tagihan = $selisih;

        $tagihan->save();
    }

    public function publish(Request $request){
        if($request->ajax()){
            $data = Tagihan::where('stt_publish',0)->orderBy('kd_kontrol','asc');
            return DataTables::of($data)
                ->editColumn('kd_kontrol', function ($data) {
                    $warna = max($data->warna_airbersih,$data->warna_listrik);
                    $hasil = $data->kd_kontrol;
                    if($warna == 1 || $warna == 2 || $warna == 3)
                        return '<span class="text-center" style="color:f4e73df;">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('nama', function ($data) {
                    $warna = max($data->warna_airbersih,$data->warna_listrik);
                    $hasil = substr($data->nama,0,13);
                    if($warna == 1 || $warna == 2 || $warna == 3)
                        return '<span class="text-center" style="color:#4e73df;">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('daya_listrik', function ($data) {
                    $warna = $data->warna_listrik;
                    $hasil = number_format($data->daya_listrik);
                    if($warna == 1 || $warna == 2)
                        return '<span style="color:#f6c23e;" class="listrik-hover;">'.$hasil.'</span>';
                    else if($warna == 3)
                        return '<span style="color:#e74a3b;background-color:rgba(255, 169, 189, 0.2);">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('awal_listrik', function ($data) {
                    $warna = $data->warna_listrik;
                    $hasil = number_format($data->awal_listrik);
                    if($warna == 1 || $warna == 2)
                        return '<span style="color:#f6c23e;" class="listrik-hover">'.$hasil.'</span>';
                    else if($warna == 3)
                        return '<span style="color:#e74a3b;background-color:rgba(255, 169, 189, 0.2);">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('akhir_listrik', function ($data) {
                    $warna = $data->warna_listrik;
                    $hasil = number_format($data->akhir_listrik);
                    if($warna == 1 || $warna == 2)
                        return '<span style="color:#f6c23e;" class="listrik-hover">'.$hasil.'</span>';
                    else if($warna == 3)
                        return '<span style="color:#e74a3b;background-color:rgba(255, 169, 189, 0.2);">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('pakai_listrik', function ($data) {
                    $warna = $data->warna_listrik;
                    $hasil = number_format($data->pakai_listrik);
                    if($warna == 1 || $warna == 2)
                        return '<span style="color:#f6c23e;" class="listrik-hover">'.$hasil.'</span>';
                    else if($warna == 3)
                        return '<span style="color:#e74a3b;background-color:rgba(255, 169, 189, 0.2);">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('ttl_listrik', function ($data) {
                    $warna = $data->warna_listrik;
                    $hasil = number_format($data->ttl_listrik);
                    if($warna == 1 || $warna == 2)
                        return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="color:#f6c23e;font-size:14px;" class="listrik-hover;"><b>'.$hasil.'</b></span></a>';
                    else if($warna == 3)
                        return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="color:#e74a3b;font-size:14px;background-color:rgba(255, 169, 189, 0.2);"><b>'.$hasil.'</b></span></a>';
                    else
                        return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('awal_airbersih', function ($data) {
                    $warna = $data->warna_airbersih;
                    $hasil = number_format($data->awal_airbersih);
                    if($warna == 1 || $warna == 2)
                        return '<span style="color:#f6c23e;" class="listrik-hover;">'.$hasil.'</span>';
                    else if($warna == 3)
                        return '<span style="color:#e74a3b;background-color:rgba(255, 169, 189, 0.2);">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('akhir_airbersih', function ($data) {
                    $warna = $data->warna_airbersih;
                    $hasil = number_format($data->akhir_airbersih);
                    if($warna == 1 || $warna == 2)
                        return '<span style="color:#f6c23e;" class="listrik-hover;">'.$hasil.'</span>';
                    else if($warna == 3)
                        return '<span style="color:#e74a3b;background-color:rgba(255, 169, 189, 0.2);">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('pakai_airbersih', function ($data) {
                    $warna = $data->warna_airbersih;
                    $hasil = number_format($data->pakai_airbersih);
                    if($warna == 1 || $warna == 2)
                        return '<span style="color:#f6c23e;" class="listrik-hover;">'.$hasil.'</span>';
                    else if($warna == 3)
                        return '<span style="color:#e74a3b;background-color:rgba(255, 169, 189, 0.2);">'.$hasil.'</span>';
                    else
                        return '<span>'.$hasil.'</span>';
                })
                ->editColumn('ttl_airbersih', function ($data) {
                    $warna = $data->warna_airbersih;
                    $hasil = number_format($data->ttl_airbersih);
                    if($warna == 1 || $warna == 2)
                        return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="color:#f6c23e;font-size:14px;" class="listrik-hover;"><b>'.$hasil.'</b></span></a>';
                    else if($warna == 3)
                        return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="color:#e74a3b;font-size:14px;background-color:rgba(255, 169, 189, 0.2);"><b>'.$hasil.'</b></span></a>';
                    else
                        return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('dis_keamananipk', function ($data) {
                    $hasil = number_format($data->dis_keamananipk);
                    return '<span>'.$hasil.'</span>';
                })
                ->editColumn('ttl_keamananipk', function ($data) {
                    $hasil = number_format($data->ttl_keamananipk);
                    return '<a href="javascript:void(0)" class="totalkeamananipk" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('dis_kebersihan', function ($data) {
                    $hasil = number_format($data->dis_kebersihan);
                    return '<span>'.$hasil.'</span>';
                })
                ->editColumn('ttl_kebersihan', function ($data) {
                    $hasil = number_format($data->ttl_kebersihan);
                    return '<a href="javascript:void(0)" class="totalkebersihan" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_airkotor', function ($data) {
                    $hasil = number_format($data->ttl_airkotor);
                    return '<a href="javascript:void(0)" class="totalairkotor" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_lain', function ($data) {
                    $hasil = number_format($data->ttl_lain);
                    return '<a href="javascript:void(0)" class="totallain" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_tagihan', function ($data) {
                    $warna = max($data->warna_airbersih,$data->warna_listrik);
                    $hasil = number_format($data->ttl_tagihan);
                    if($warna == 1 || $warna == 2 || $warna == 3)
                        return '<a href="javascript:void(0)" class="totaltagihan" id="'.$data->id.'"><span style="color:#4e73df;font-size:14px;"><b>'.$hasil.'</b></span></a>';
                    else
                        return '<a href="javascript:void(0)" class="totaltagihan" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ket', function ($data) {
                    return '<span style="white-space:normal;">'.$data->ket.'</span>';
                })
                ->addColumn('verifikasi', function($data){
                    if($data->review === 0){
                        $button = '<button type="button" title="Verifikasi" name="verifikasi" id="'.$data->id.'" class="verification btn btn-sm btn-danger">checking</button><br><label>'.$data->reviewer.'</label>';
                    }
                    else if($data->review === 2){
                        $button = '<button type="button" title="Verifikasi" name="verifikasi" id="'.$data->id.'" class="verification btn btn-sm btn-primary">checked</button><br><label>'.$data->reviewer.'</label>';
                    }
                    else{
                        $button = '<button type="button" title="Verifikasi" name="verifikasi" id="'.$data->id.'" class="verification btn btn-sm btn-success">verified</button><br><label>'.$data->reviewer.'</label>';
                    }
                    return $button;
                })
                ->rawColumns([
                    'kd_kontrol',
                    'nama',
                    'verifikasi',
                    'ket',
                    'daya_listrik',
                    'awal_listrik',
                    'akhir_listrik',
                    'pakai_listrik',
                    'ttl_listrik',
                    'awal_airbersih',
                    'akhir_airbersih',
                    'pakai_airbersih',
                    'ttl_airbersih',
                    'dis_keamananipk',
                    'ttl_keamananipk',
                    'dis_kebersihan',
                    'ttl_kebersihan',
                    'ttl_airkotor',
                    'ttl_lain',
                    'ttl_tagihan',
                ])
                ->make(true);
        }
        if(Session::get('role') == 'master' || Session::get('role') == 'admin' && Session::get('otoritas')->publish)
            return view('tagihan.publish');
        else
            abort(403);
    }

    public function publishStore(Request $request){
        if($request->ajax()){
            try{
                $dataset = Tagihan::where('stt_publish',0)->get();
                foreach($dataset as $d){
                    $d->review      = 1;
                    $d->reviewer    = Session::get('username');
                    $d->stt_publish = 1;
                    $d->via_publish = Session::get('username');
                    $d->save();
                }
                return response()->json(['success' => 'Publish Sukses']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Publish Gagal']);
            }
        }
    }

    public function pesan(Request $request,$id){
        if($request->ajax()){
            try{
                $tagihan = Tagihan::find($id);
                $data = array();
                $data['pesan']  = $tagihan->ket;
                $data['status'] = $tagihan->review;
                return response()->json(['result' => $data]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Ambil Data Gagal']);
            }
        }
    }


    public function reviewStore(Request $request){
        if($request->ajax()){
            $id = $request->hidden_id;
            try{
                //1 = Verified
                //0 = Checking
                //2 = Edited
                $id = $request->hidden_id;
                $tagihan = Tagihan::find($id);
                $tagihan->ket      =  "(" . substr(Session::get('username'),0,6) . " at " . date('d/m/Y h:i',strtotime(Carbon::now())) . ") : " . $request->pesan;
                $tagihan->review   = 0;
                $tagihan->reviewer = "by ".Session::get('username');
                $tagihan->save();
                return response()->json(['success' => 'Pengecekan Diajukan']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => "Oops ! Pengajuan Check Tagihan Gagal"]);
            }
        }
    }

    public function review(Request $request, $id){
        if($request->ajax()){
            try{
                //1 = Verified
                //0 = Checking
                //2 = Edited
                $tagihan = Tagihan::find($id);
                $tagihan->review = 1;
                $tagihan->reviewer = "Verified by ".Session::get('username');
                $tagihan->save();
                return response()->json(['success' => 'Tagihan Verified']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Verifikasi Gagal']);
            }
        }
    }

    public function notification(Request $request){
        if(Session::get('role') == 'admin')
            $wherein = Session::get('otoritas')->otoritas;

        if($request->ajax())
        {
            if(Session::get('role') == 'admin'){
                $wherein = Session::get('otoritas')->otoritas;
                $data = Tagihan::where('review',0)->whereIn('blok',$wherein);
            }
            else{
                $data = Tagihan::where('review',0);
            }

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
                ->editColumn('daya_listrik', function ($data) {
                    $hasil = number_format($data->daya_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->daya_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('awal_listrik', function ($data) {
                    $hasil = number_format($data->awal_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->awal_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_listrik', function ($data) {
                    $hasil = number_format($data->akhir_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->akhir_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_listrik', function ($data) {
                    $hasil = number_format($data->pakai_listrik);
                    $warna = $data->warna_listrik;
                    if ($data->pakai_listrik === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_listrik', function ($data) {
                    $warna = $data->warna_listrik;
                    $hasil = number_format($data->ttl_listrik);
                    if($warna == 1 || $warna == 2)
                        return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="color:#f6c23e;font-size:14px;" class="listrik-hover;"><b>'.$hasil.'</b></span></a>';
                    else if($warna == 3)
                        return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="color:#e74a3b;font-size:14px;background-color:rgba(255, 169, 189, 0.2);"><b>'.$hasil.'</b></span></a>';
                    else
                        return '<a href="javascript:void(0)" class="totallistrik" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('awal_airbersih', function ($data) {
                    $hasil = number_format($data->awal_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->awal_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('akhir_airbersih', function ($data) {
                    $hasil = number_format($data->akhir_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->akhir_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('pakai_airbersih', function ($data) {
                    $hasil = number_format($data->pakai_airbersih);
                    $warna = $data->warna_airbersih;
                    if ($data->pakai_airbersih === NULL)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else {
                        if($warna == 1 || $warna == 2)
                            return '<span class="text-center" style="color:#f6c23e;">'.$hasil.'</span>';
                        else if($warna == 3)
                            return '<span class="text-center" style="color:#e74a3b;">'.$hasil.'</span>';
                        else
                            return $hasil;
                    }
                })
                ->editColumn('ttl_airbersih', function ($data) {
                    $hasil = number_format($data->ttl_airbersih);
                    $warna = $data->warna_airbersih;
                    if($warna == 1 || $warna == 2)
                        return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="color:#f6c23e;font-size:14px;" class="listrik-hover;"><b>'.$hasil.'</b></span></a>';
                    else if($warna == 3)
                        return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="color:#e74a3b;font-size:14px;background-color:rgba(255, 169, 189, 0.2);"><b>'.$hasil.'</b></span></a>';
                    else
                        return '<a href="javascript:void(0)" class="totalairbersih" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->editColumn('ttl_keamananipk', function ($data) {
                    $hasil = number_format($data->ttl_keamananipk);
                    if ($data->ttl_keamananipk == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else 
                        return $hasil;
                })
                ->editColumn('ttl_kebersihan', function ($data) {
                    $hasil = number_format($data->ttl_kebersihan);
                    if ($data->ttl_kebersihan == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return $hasil;
                })
                ->editColumn('ttl_airkotor', function ($data) {
                    $hasil = number_format($data->ttl_airkotor);
                    if ($data->ttl_airkotor == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return $hasil;
                })
                ->editColumn('ttl_lain', function ($data) {
                    $hasil = number_format($data->ttl_lain);
                    if ($data->ttl_lain == 0)
                        return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else
                        return $hasil;
                })
                ->editColumn('ttl_tagihan', function ($data) {
                    $hasil = number_format($data->ttl_tagihan);
                    $warna = max($data->warna_airbersih, $data->warna_listrik);
                    if($warna == 1 || $warna == 2 || $warna == 3)
                        return '<a href="javascript:void(0)" class="totaltagihan" id="'.$data->id.'"><span style="color:#4e73df;font-size:14px;"><b>'.$hasil.'</b></span></a>';
                    else
                        return '<a href="javascript:void(0)" class="totaltagihan" id="'.$data->id.'"><span style="font-size:14px;color:#000000;"><b>'.$hasil.'</b></span></a>';
                })
                ->rawColumns([
                    'action',
                    'los',
                    'kd_kontrol',
                    'nama',
                    'daya_listrik',
                    'awal_listrik',
                    'akhir_listrik',
                    'pakai_listrik',
                    'ttl_listrik',
                    'awal_airbersih',
                    'akhir_airbersih',
                    'pakai_airbersih',
                    'ttl_airbersih',
                    'ttl_keamananipk',
                    'ttl_kebersihan',
                    'ttl_airkotor',
                    'ttl_lain',
                    'ttl_tagihan',
                ])
                ->make(true);
        }

        if(Session::get('role') == 'admin'){
            return view('tagihan.notification');
        }
        else{
            return view('tagihan.notification');
        }
    }

    public function tambah(Request $request){
        if($request->ajax()){
            try{
                $now = date('Y-m-d',strtotime(Carbon::now()));
                $check = date('Y-m-20',strtotime(Carbon::now()));

                if($now < $check){
                    $tgl_tagihan = date('Y-m-01',strtotime(Carbon::now()));
                    $time = strtotime($tgl_tagihan);
                    $bln_tagihan = date("Y-m", strtotime($tgl_tagihan));
                    $bln_pakai = date("Y-m", strtotime("-1 month", $time));
                    $thn_tagihan = date("Y", strtotime($tgl_tagihan));
                    $expired = date("Y-m-15", strtotime($tgl_tagihan));
                }
                else if($now >= $check){
                    $tgl_tagihan = date('Y-m-01',strtotime(Carbon::now()));
                    $tgl_tagihan = new Carbonet($tgl_tagihan, 1);
                    $bln_tagihan = date("Y-m", strtotime($tgl_tagihan));
                    $bln_pakai = date("Y-m", strtotime(Carbon::now()));
                    $thn_tagihan = date("Y", strtotime($tgl_tagihan));
                    $expired = date("Y-m-15", strtotime($tgl_tagihan));
                }

                do{
                    $libur = HariLibur::where('tanggal', $expired)->first();
                    if ($libur != NULL){
                        $stop_date = strtotime($expired);
                        $expired = date("Y-m-d", strtotime("+1 day", $stop_date));
                        $done = TRUE;
                    }
                    else{
                        $done = FALSE;
                    }
                }
                while($done == TRUE);
                $tgl_expired = $expired;

                $periode = $tgl_tagihan;
                
                $tagihan              = new Tagihan;
                //Global
                $record               = TempatUsaha::find($request->kontrol_manual);
                $tagihan->nama        = $request->pengguna_manual;
                $tagihan->blok        = $record->blok;
                $tagihan->kd_kontrol  = $record->kd_kontrol;
                $tagihan->bln_pakai   = $bln_pakai;
                $tagihan->tgl_tagihan = $tgl_tagihan;
                $tagihan->bln_tagihan = $bln_tagihan;
                $tagihan->thn_tagihan = $thn_tagihan;
                $tagihan->tgl_expired = $tgl_expired;
                $tagihan->stt_lunas   = 0;
                $tagihan->stt_bayar   = 0;
                $tagihan->via_tambah  = Session::get('username');
                $tagihan->stt_publish = 0;
                
                //Listrik
                if($request->stt_listrik_manual == 'ok'){
                    if($record->trf_listrik !== NULL){
                        if($record->id_meteran_listrik !== NULL){
                            $awal = explode(',',$request->awalListrik_manual);
                            $awal = implode('',$awal);
                            
                            $akhir = explode(',',$request->akhirListrik_manual);
                            $akhir = implode('',$akhir);
                            
                            $daya = explode(',',$request->dayaListrik_manual);
                            $daya = implode('',$daya);

                            $tagihan->awal_listrik = $awal;
                            $tagihan->akhir_listrik = $akhir;
                            $tagihan->daya_listrik = $daya;

                            $meter = AlatListrik::find($record->id_meteran_listrik);
                            if($meter != NULL){
                                $meter->daya =  $daya;
                                $meter->akhir =  $akhir; 
                                $meter->save();
                            }

                            $tarif = TarifListrik::find(1);

                            $batas_rekmin = round(18 * $daya /1000);
                            
                            if($akhir >= $awal)
                                $pakai_listrik = $akhir - $awal;
                            else{
                                if($awal < 1000)
                                    $denom = 1000;
                                else if($awal < 10000)
                                    $denom = 10000;
                                else if($awal < 100000)
                                    $denom = 100000;
                                else if($awal < 1000000)
                                    $denom = 1000000;
                                else{
                                    abort(500);
                                }
                                
                                $pakai_listrik = $denom - $awal;
                                $pakai_listrik = $pakai_listrik + $akhir;
                            }

                            $a = round(($daya * $tarif->trf_standar) / 1000);
                            $blok1_listrik = $tarif->trf_blok1 * $a;

                            $b = $pakai_listrik - $a;
                            $blok2_listrik = $tarif->trf_blok2 * $b;
                            $beban_listrik = $daya * $tarif->trf_beban;

                            $c = $blok1_listrik + $blok2_listrik + $beban_listrik;
                            $rekmin_listrik = 53.44 * $daya;

                            if($pakai_listrik <= $batas_rekmin){
                                $bpju_listrik = ($tarif->trf_bpju / 100) * $rekmin_listrik;
                                $blok1_listrik = 0;
                                $blok2_listrik = 0;
                                $beban_listrik = 0;
                                $byr_listrik = $bpju_listrik + $rekmin_listrik;
                                $ttl_listrik = $byr_listrik;
                            }
                            else{
                                $bpju_listrik = ($tarif->trf_bpju / 100) * $c;
                                $rekmin_listrik = 0;
                                $byr_listrik = $bpju_listrik + $blok1_listrik + $blok2_listrik + $beban_listrik;
                                $ttl_listrik = $byr_listrik;
                            }

                            $tagihan->daya_listrik = $daya;
                            $tagihan->awal_listrik = $awal;
                            $tagihan->akhir_listrik = $akhir;
                            $tagihan->pakai_listrik = $pakai_listrik;
                            $tagihan->byr_listrik = $byr_listrik;
                            $tagihan->rekmin_listrik = $rekmin_listrik;
                            $tagihan->blok1_listrik = $blok1_listrik;
                            $tagihan->blok2_listrik = $blok2_listrik;
                            $tagihan->beban_listrik = $beban_listrik;
                            $tagihan->bpju_listrik = $bpju_listrik;

                            $tagihan->sub_listrik = round($ttl_listrik  + ($ttl_listrik * ($tarif->trf_ppn / 100)));

                            $tempat = TempatUsaha::where('kd_kontrol',$record->kd_kontrol)->first();
                            $diskon = 0;
                            if($tempat != NULL){
                                if($tempat->dis_listrik === NULL){
                                    $diskon = 0;
                                }
                                else{
                                    $diskon = $tempat->dis_listrik;
                                    $diskon = ($tagihan->sub_listrik * $diskon) / 100;
                                }
                            }
                            else{
                                $diskon = $tagihan->dis_listrik;
                                if($diskon > $tagihan->sub_listrik){
                                    $diskon = $tagihan->sub_listrik;
                                }
                            }
                            
                            $total = $tagihan->sub_listrik - $diskon; 
                            $tagihan->ttl_listrik = $total + $tagihan->den_listrik;
                            $tagihan->sel_listrik = $tagihan->ttl_listrik - $tagihan->rea_listrik;

                            $warna = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_listrik',1]])->orderBy('id','desc')->limit(3)->get();
                            if($warna != NULL){
                                $warna_ku = 0;
                                foreach($warna as $war){
                                    $warna_ku = $warna_ku + $war->pakai_listrik;
                                }
                                $warna = round($warna_ku / 3);
                            
                                $lima_persen             = $warna * (5/100);
                                $seratussepuluh_persen   = ($warna * (110/100)) + $warna;
                                $seratuslimapuluh_persen = ($warna * (150/100)) + $warna;
                                
                                if($pakai_listrik <= $lima_persen){
                                    $warna = 1;
                                }
                                else if($pakai_listrik >= $seratussepuluh_persen && $pakai_listrik < $seratuslimapuluh_persen){
                                    $warna = 2;
                                }
                                else if($pakai_listrik >= $seratuslimapuluh_persen){
                                    $warna = 3;
                                }
                                else{
                                    $warna = 0;
                                }
                            }
                            else{
                                $warna = 0;
                            }

                            $tagihan->warna_listrik = $warna;
                        }
                        $tagihan->stt_listrik = 1;
                    }
                }

                // Air Bersih
                if($request->stt_airbersih_manual == 'ok'){
                    if($record->trf_airbersih !== NULL){
                        if($record->id_meteran_air !== NULL){
                            $awal = explode(',',$request->awalAir_manual);
                            $awal = implode('',$awal);
                            
                            $akhir = explode(',',$request->akhirAir_manual);
                            $akhir = implode('',$akhir);

                            $tagihan->awal_airbersih = $awal;
                            $tagihan->akhir_airbersih = $akhir;

                            $meter = AlatAir::find($record->id_meteran_air);
                            if($meter != NULL){
                                $meter->akhir =  $akhir; 
                                $meter->save();
                            }

                            $tarif = TarifAirBersih::find(1);

                            if($akhir >= $awal)
                                $pakai_airbersih = $akhir - $awal;
                            else{
                                if($awal < 1000)
                                    $denom = 1000;
                                else if($awal < 10000)
                                    $denom = 10000;
                                else if($awal < 100000)
                                    $denom = 100000;
                                else if($awal < 1000000)
                                    $denom = 1000000;
                                else{
                                    abort(500);
                                }
                                
                                $pakai_airbersih = $denom - $awal;
                                $pakai_airbersih = $pakai_airbersih + $akhir;
                            }

                            if($pakai_airbersih > 10){
                                $a = 10 * $tarif->trf_1;
                                $b = ($pakai_airbersih - 10) * $tarif->trf_2;
                                $byr_airbersih = $a + $b;
                        
                                $pemeliharaan_airbersih = $tarif->trf_pemeliharaan;
                                $beban_airbersih = $tarif->trf_beban;
                                $arkot_airbersih = ($tarif->trf_arkot / 100) * $byr_airbersih;

                                $ttl_airbersih = $byr_airbersih + $pemeliharaan_airbersih + $beban_airbersih + $arkot_airbersih;
                            }
                            else{      
                                $byr_airbersih = $pakai_airbersih * $tarif->trf_1;
                        
                                $pemeliharaan_airbersih = $tarif->trf_pemeliharaan;
                                $beban_airbersih = $tarif->trf_beban;
                                $arkot_airbersih = ($tarif->trf_arkot / 100) * $byr_airbersih;

                                $ttl_airbersih = $byr_airbersih + $pemeliharaan_airbersih + $beban_airbersih + $arkot_airbersih;
                            }

                            $tagihan->awal_airbersih = $awal;
                            $tagihan->akhir_airbersih = $akhir;
                            $tagihan->pakai_airbersih = $pakai_airbersih;
                            $tagihan->byr_airbersih = $byr_airbersih;
                            $tagihan->pemeliharaan_airbersih = $pemeliharaan_airbersih;
                            $tagihan->beban_airbersih = $beban_airbersih;
                            $tagihan->arkot_airbersih = $arkot_airbersih;
                            $tagihan->sub_airbersih = round($ttl_airbersih + ($ttl_airbersih * ($tarif->trf_ppn / 100)));

                            $tempat = TempatUsaha::where('kd_kontrol',$record->kd_kontrol)->first();
                            $diskon = 0;
                            if($tempat != NULL){
                                if($tempat->dis_airbersih === NULL){
                                    $diskon = 0;
                                }
                                else{
                                    $diskon = json_decode($tempat->dis_airbersih);
                                    if($diskon->type == 'diskon'){
                                        $diskon = $diskon->value;
                                        $diskon = ($tagihan->sub_airbersih * $diskon) / 100;
                                    }
                                    else{
                                        $disc = 0;
                                        $diskonArray = $diskon->value;
                                        if(in_array('byr',$diskonArray)){
                                            $disc = $disc + $tagihan->byr_airbersih;
                                        }
                                        if(in_array('beban',$diskonArray)){
                                            $disc = $disc + $tagihan->beban_airbersih;
                                        }
                                        if(in_array('pemeliharaan',$diskonArray)){
                                            $disc = $disc + $tagihan->pemeliharaan_airbersih;
                                        }
                                        if(in_array('arkot',$diskonArray)){
                                            $disc = $disc + $tagihan->arkot_airbersih;
                                        }
                                        if(is_object($diskonArray[count($diskonArray) - 1])){
                                            $charge = $diskonArray[count($diskonArray) - 1]->charge;
                                            $charge = explode(',',$charge);
                                            $persen = $charge[0];
                                            if($charge[1] == 'ttl'){
                                                $sale = $tagihan->sub_airbersih * ($persen / 100);
                                            }
                                            if($charge[1] == 'byr'){
                                                $sale = $tagihan->byr_airbersih * ($persen / 100); 
                                            }
                                            $disc = $disc + $sale;
                                        }
                                        
                                        $disc   = $disc + ($disc * ($tarif->trf_ppn / 100));
                                        $disc   = $tagihan->sub_airbersih - $disc;
                                        $diskon = $disc;
                                    }
                                }
                            }
                            else{
                                $diskon = $tagihan->dis_airbersih;
                                if($diskon > $tagihan->sub_airbersih){
                                    $diskon = $tagihan->sub_airbersih;
                                }
                            }

                            $total = $tagihan->sub_airbersih - $diskon; 
                            $tagihan->ttl_airbersih = $total + $tagihan->den_airbersih;
                            $tagihan->sel_airbersih = $tagihan->ttl_airbersih - $tagihan->rea_airbersih;

                            $warna = Tagihan::where([['kd_kontrol',$tagihan->kd_kontrol],['stt_publish',1],['stt_airbersih',1]])->orderBy('id','desc')->limit(3)->get();
                            if($warna != NULL){
                                $warna_ku = 0;
                                foreach($warna as $war){
                                    $warna_ku = $warna_ku + $war->pakai_airbersih;
                                }
                                $warna = round($warna_ku / 3);
                            
                                $lima_persen             = $warna * (5/100);
                                $seratussepuluh_persen   = ($warna * (110/100)) + $warna;
                                $seratuslimapuluh_persen = ($warna * (150/100)) + $warna;
                                
                                if($pakai_airbersih <= $lima_persen){
                                    $warna = 1;
                                }
                                else if($pakai_airbersih >= $seratussepuluh_persen && $pakai_airbersih < $seratuslimapuluh_persen){
                                    $warna = 2;
                                }
                                else if($pakai_airbersih >= $seratuslimapuluh_persen){
                                    $warna = 3;
                                }
                                else{
                                    $warna = 0;
                                }
                            }
                            else{
                                $warna = 0;
                            }

                            $tagihan->warna_airbersih = $warna;
                        }
                        $tagihan->stt_airbersih = 1;
                    }
                }

                $tagihan->jml_alamat  = $record->jml_alamat;
                $tagihan->no_alamat   = $record->no_alamat;
                $tagihan->lok_tempat  = $record->lok_tempat;

                //KeamananIPK
                if($request->stt_keamananipk_manual == 'ok'){
                    if($record->trf_keamananipk !== NULL){
                        $diskon = 0;
                        if($request->keamananipk_manual == NULL || $request->keamananipk_manual == 0){
                            $keamananipk = 0;
                            $dis         = 0;
                        }
                        else{
                            $keamananipk = explode(',',$request->keamananipk_manual);
                            $keamananipk = implode('',$keamananipk);
                            
                            if($request->dis_keamananipk_manual != NULL || $request->dis_keamananipk_manual != 0){
                                $diskon = explode(',',$request->dis_keamananipk_manual);
                                $diskon = implode('',$diskon);
                            }
                        }
                        $tagihan->sub_keamananipk = $keamananipk;
                        $tagihan->dis_keamananipk = $diskon;
                        $tagihan->ttl_keamananipk = $tagihan->sub_keamananipk - $tagihan->dis_keamananipk;
                        $tarif = TarifKeamananIpk::find($record->trf_keamananipk);
                        $tagihan->ttl_keamanan    = ($tarif->prs_keamanan / 100) * $tagihan->ttl_keamananipk;
                        $tagihan->ttl_ipk         = ($tarif->prs_ipk / 100) * $tagihan->ttl_keamananipk;
                        $tagihan->rea_keamananipk = 0;
                        $tagihan->sel_keamananipk = $tagihan->ttl_keamananipk;
                        $tagihan->stt_keamananipk = 1;
                    }
                }

                //Kebersihan
                if($request->stt_kebersihan_manual == 'ok'){
                    if($record->trf_kebersihan !== NULL){
                        $diskon = 0;
                        if($request->kebersihan_manual == NULL || $request->kebersihan_manual == 0){
                            $kebersihan = 0;
                            $diskon      = 0;
                        }
                        else{
                            $kebersihan = explode(',',$request->kebersihan_manual);
                            $kebersihan = implode('',$kebersihan);
                            
                            if($request->dis_kebersihan_manual != NULL || $request->dis_kebersihan_manual != 0){
                                $diskon = explode(',',$request->dis_kebersihan_manual);
                                $diskon = implode('',$diskon);
                            }
                        }
                        $tagihan->sub_kebersihan = $kebersihan;
                        $tagihan->dis_kebersihan = $diskon;
                        $tagihan->ttl_kebersihan = $tagihan->sub_kebersihan - $tagihan->dis_kebersihan;
                        $tagihan->rea_kebersihan = 0;
                        $tagihan->sel_kebersihan = $tagihan->ttl_kebersihan;
                        $tagihan->stt_kebersihan = 1;
                    }
                }

                // //Air Kotor
                if($request->stt_airkotor_manual == 'ok'){
                    if($record->trf_airkotor !== NULL){
                        if($request->airkotor_manual == NULL || $request->airkotor_manual == 0){
                            $airkotor = 0;
                        }
                        else{
                            $airkotor = explode(',',$request->airkotor_manual);
                            $airkotor = implode('',$airkotor);
                        }
                        $tagihan->ttl_airkotor = $airkotor;
                        $tagihan->rea_airkotor = 0;
                        $tagihan->sel_airkotor = $tagihan->ttl_airkotor;
                        $tagihan->stt_airkotor = 1;
                    }
                }

                // //Lain - Lain
                if($request->stt_lain_manual == 'ok'){
                    if($record->trf_lain !== NULL){
                        if($request->lain_manual == NULL || $request->lain_manual == 0){
                            $lain = 0;
                        }
                        else{
                            $lain = explode(',',$request->lain_manual);
                            $lain = implode('',$lain);
                        }
                        $tagihan->ttl_lain = $lain;
                        $tagihan->rea_lain = 0;
                        $tagihan->sel_lain = $tagihan->ttl_lain;
                        $tagihan->stt_lain = 1;
                    }
                }

                //Subtotal
                $subtotal = 
                        $tagihan->sub_listrik     + 
                        $tagihan->sub_airbersih   + 
                        $tagihan->sub_keamananipk + 
                        $tagihan->sub_kebersihan  + 
                        $tagihan->ttl_airkotor    + 
                        $tagihan->ttl_lain;
                $tagihan->sub_tagihan = $subtotal;

                //Diskon
                $diskon = 
                    $tagihan->dis_listrik     + 
                    $tagihan->dis_airbersih   + 
                    $tagihan->dis_keamananipk + 
                    $tagihan->dis_kebersihan;
                $tagihan->dis_tagihan = $diskon;

                //Denda
                $tagihan->den_tagihan = $tagihan->den_listrik + $tagihan->den_airbersih;

                //TOTAL
                $total = 
                    $tagihan->ttl_listrik     + 
                    $tagihan->ttl_airbersih   + 
                    $tagihan->ttl_keamananipk + 
                    $tagihan->ttl_kebersihan  + 
                    $tagihan->ttl_airkotor    + 
                    $tagihan->ttl_lain;
                $tagihan->ttl_tagihan = $total;

                //Realisasi
                $realisasi = 
                        $tagihan->rea_listrik     + 
                        $tagihan->rea_airbersih   + 
                        $tagihan->rea_keamananipk + 
                        $tagihan->rea_kebersihan  + 
                        $tagihan->rea_airkotor    + 
                        $tagihan->rea_lain;
                $tagihan->rea_tagihan = $realisasi;

                //Selisih
                $selisih =
                        $tagihan->sel_listrik     + 
                        $tagihan->sel_airbersih   + 
                        $tagihan->sel_keamananipk + 
                        $tagihan->sel_kebersihan  + 
                        $tagihan->sel_airkotor    + 
                        $tagihan->sel_lain;
                $tagihan->sel_tagihan = $selisih;

                $tagihan->save();

                return response()->json(['success' => 'Data Tagihan Ditambah.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }
        }
    }

    public function pemberitahuan($blok){
        $date = date("Y-m-d",strtotime(Carbon::now()));
        $check = date("Y-m-20",strtotime(Carbon::now()));

        if($date <= $check){
            $tanggal = date('Y-m-01',strtotime(Carbon::now()));
            $bulan = date('Y-m',strtotime(Carbon::now()));
            $bulan = IndoDate::bulanS($bulan,' ');
        }
        
        if($date > $check){
            $tanggal = date('Y-m-01',strtotime(Carbon::now()));
            $tanggal = new Carbonet($tanggal, 1);
            $bulan = date('Y-m',strtotime($tanggal));
            $bulan = IndoDate::bulanS($bulan,' ');
        }


        $dataset = Tagihan::where([['stt_lunas',0],['tgl_tagihan',$tanggal],['blok',$blok]])->orderBy('kd_kontrol','asc')->get();

        $i = 0;
        $pemberitahuan = array();
        foreach($dataset as $d){
            $pemberitahuan[$i]['alamat'] = $d->no_alamat;
            $pemberitahuan[$i]['lokasi'] = $d->lok_tempat;
            if($d->lok_tempat == NULL){

                $pemberitahuan[$i]['lokasi'] = '-';
            }

            //Listrik
            $listrik = $d->ttl_listrik;
            if($listrik != 0){
                $awal_listrik = $d->awal_listrik;
                $akhir_listrik = $d->akhir_listrik;
                $pakai_listrik = $d->pakai_listrik;
                $pemberitahuan[$i]['daya_listrik'] = $d->daya_listrik;
                $pemberitahuan[$i]['awal_listrik'] = $awal_listrik;
                $pemberitahuan[$i]['akhir_listrik'] = $akhir_listrik;
                $pemberitahuan[$i]['pakai_listrik'] = $pakai_listrik;
            }

            //AirBersih
            $airbersih = $d->ttl_airbersih;
            if($airbersih != 0){
                $awal_airbersih = $d->awal_airbersih;
                $akhir_airbersih = $d->akhir_airbersih;
                $pakai_airbersih = $d->pakai_airbersih;
                $pemberitahuan[$i]['awal_airbersih'] = $awal_airbersih;
                $pemberitahuan[$i]['akhir_airbersih'] = $akhir_airbersih;
                $pemberitahuan[$i]['pakai_airbersih'] = $pakai_airbersih;
            }

            //KeamananIpk
            $keamananipk = $d->ttl_keamananipk;
            
            //Kebersihan
            $kebersihan = $d->ttl_kebersihan;

            //AirKotor
            $airkotor = $d->ttl_airkotor;

            //Lain
            $lain = $d->ttl_lain;

            $pemberitahuan[$i][0] = $d->kd_kontrol;
            $pemberitahuan[$i][1] = $d->nama;

            $pemberitahuan[$i][2] = $listrik;
            $pemberitahuan[$i][3] = $airbersih;
            $pemberitahuan[$i][4] = $keamananipk;
            $pemberitahuan[$i][5] = $kebersihan;
            $pemberitahuan[$i][6] = $airkotor;

            $tunggakan = 0;
            $denda = 0;

            $tagihan = Tagihan::where([['stt_lunas',0],['tgl_tagihan','<',$tanggal],['kd_kontrol',$d->kd_kontrol]])->get();
            foreach($tagihan as $t){
                $tunggakan = $tunggakan + $t->sel_tagihan;
                $denda = $denda + $t->den_tagihan;
                $lain = $lain + $t->sel_lain;
            }

            $pemberitahuan[$i][7] = $tunggakan - $denda;
            $pemberitahuan[$i][8] = $denda;
            $pemberitahuan[$i][9] = $lain;

            $total = 0;
            for($j = 2; $j <= 9; $j++){
                $total = $total + $pemberitahuan[$i][$j];
            }
            $pemberitahuan[$i]['total']     = $total;
            $pemberitahuan[$i]['terbilang'] = '('.ucfirst(Terbilang::convert($total)).')';
            
            $i++;
        }

        return view('tagihan.pemberitahuan',['blok' => $blok,'bulan' => $bulan, 'dataset' => $pemberitahuan]);
    }

    public function pembayaran($blok){
        if(Session::get('role') == 'master'){
            $date = date("Y-m-d",strtotime(Carbon::now()));
            $check = date("Y-m-20",strtotime(Carbon::now()));

            if($date <= $check){
                $tanggal = date('Y-m-01',strtotime(Carbon::now()));
                $bulan = date('Y-m',strtotime(Carbon::now()));
                $bulan = IndoDate::bulanS($bulan,' ');
            }
            
            if($date > $check){
                $tanggal = date('Y-m-01',strtotime(Carbon::now()));
                $tanggal = new Carbonet($tanggal, 1);
                $bulan = date('Y-m',strtotime($tanggal));
                $bulan = IndoDate::bulanS($bulan,' ');
            }


            $dataset = Tagihan::where([['stt_lunas',0],['tgl_tagihan',$tanggal],['blok',$blok]])->orderBy('kd_kontrol','asc')->get();

            $i = 0;
            $pemberitahuan = array();
            $no_faktur = "";
            foreach($dataset as $d){
                if($d->no_faktur === NULL){
                    $faktur = Sinkronisasi::where('sinkron', $tanggal)->first();
                    $tgl_faktur = $faktur->sinkron;
                    $nomor = $faktur->faktur + 1;
                    $faktur->faktur = $nomor;
                    if($nomor < 10)
                        $nomor = '000'.$nomor;
                    else if($nomor >= 10 && $nomor < 100)
                        $nomor = '00'.$nomor;
                    else if($nomor >= 100 && $nomor < 1000)
                        $nomor = '0'.$nomor;
                    else
                        $nomor = $nomor;

                    $no_faktur = $nomor.'/'.str_replace('-','/',$tgl_faktur);
                    $faktur->save();
                }
                else{
                    $no_faktur = $d->no_faktur;
                }

                $d->no_faktur = $no_faktur;
                $d->save();

                $pemberitahuan[$i]['alamat'] = $d->no_alamat;
                $pemberitahuan[$i]['lokasi'] = $d->lok_tempat;
                if($d->lok_tempat == NULL){
                    $pemberitahuan[$i]['lokasi'] = '-';
                }

                //Listrik
                $listrik = $d->ttl_listrik;
                if($listrik != 0){
                    $awal_listrik = $d->awal_listrik;
                    $akhir_listrik = $d->akhir_listrik;
                    $pakai_listrik = $d->pakai_listrik;
                    $pemberitahuan[$i]['daya_listrik'] = $d->daya_listrik;
                    $pemberitahuan[$i]['awal_listrik'] = $awal_listrik;
                    $pemberitahuan[$i]['akhir_listrik'] = $akhir_listrik;
                    $pemberitahuan[$i]['pakai_listrik'] = $pakai_listrik;
                }

                //AirBersih
                $airbersih = $d->ttl_airbersih;
                if($airbersih != 0){
                    $awal_airbersih = $d->awal_airbersih;
                    $akhir_airbersih = $d->akhir_airbersih;
                    $pakai_airbersih = $d->pakai_airbersih;
                    $pemberitahuan[$i]['awal_airbersih'] = $awal_airbersih;
                    $pemberitahuan[$i]['akhir_airbersih'] = $akhir_airbersih;
                    $pemberitahuan[$i]['pakai_airbersih'] = $pakai_airbersih;
                }

                //KeamananIpk
                $keamananipk = $d->ttl_keamananipk;
                
                //Kebersihan
                $kebersihan = $d->ttl_kebersihan;

                //AirKotor
                $airkotor = $d->ttl_airkotor;

                //Lain
                $lain = $d->ttl_lain;

                $pemberitahuan[$i][0] = $d->kd_kontrol;
                $pemberitahuan[$i][1] = $d->nama;

                $pemberitahuan[$i][2] = $listrik;
                $pemberitahuan[$i][3] = $airbersih;
                $pemberitahuan[$i][4] = $keamananipk;
                $pemberitahuan[$i][5] = $kebersihan;
                $pemberitahuan[$i][6] = $airkotor;

                $tunggakan = 0;
                $denda = 0;

                $tagihan = Tagihan::where([['stt_lunas',0],['tgl_tagihan','<',$tanggal],['kd_kontrol',$d->kd_kontrol]])->get();
                foreach($tagihan as $t){
                    $tunggakan = $tunggakan + $t->sel_tagihan;
                    $denda = $denda + $t->den_tagihan;
                    $lain = $lain + $t->sel_lain;
                    $t->no_faktur = $no_faktur;
                    $t->save();
                }

                $pemberitahuan[$i][7] = $tunggakan - $denda;
                $pemberitahuan[$i][8] = $denda;
                $pemberitahuan[$i][9] = $lain;

                $total = 0;
                for($j = 2; $j <= 9; $j++){
                    $total = $total + $pemberitahuan[$i][$j];
                }
                $pemberitahuan[$i]['total']     = $total;
                $pemberitahuan[$i]['terbilang'] = '('.ucfirst(Terbilang::convert($total)).')';
                $pemberitahuan[$i]['faktur']    = $no_faktur;
                
                $i++;
            }

            return view('tagihan.pembayaran',['blok' => $blok,'bulan' => $bulan, 'dataset' => $pemberitahuan]);
        }
        else{
            return redirect()->back();
        }
    }

    public function unpublish(Request $request,$id){
        if($request->ajax()){
            try{
                $tagihan = Tagihan::find($id);
                $publish = $tagihan->stt_publish;
                if($publish === 1){
                    $pembayaran = Pembayaran::where('id_tagihan',$tagihan->id)->first();
                    if($pembayaran == NULL)
                        $hasil = 0;
                    else
                        return response()->json(['unsuccess' => 'Unpublish Gagal, Pembayaran telah dilakukan']);
                }
                else if($publish === 0){
                    $hasil = 1;
                }
                $tagihan->review      = 1;
                $tagihan->reviewer    = Session::get('username');
                $tagihan->stt_publish = $hasil;
                $tagihan->via_publish = Session::get('username');
                $tagihan->save();
                if($hasil == 0)
                    return response()->json(['success' => 'Unpublish Sukses']);
                else
                    return response()->json(['success' => 'Publish Sukses']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Gagal Unpublish']);
            }
        }
    }
    
    public function manual(Request $request, $id){
        if($request->ajax()){
            try{
                $tempat = TempatUsaha::find($id);

                $data['kontrol'] = $tempat->kd_kontrol;

                $data['listrik'] = $tempat->trf_listrik;
                $data['airbersih'] = $tempat->trf_airbersih;
                $data['keamananipk'] = $tempat->trf_keamananipk;
                $data['kebersihan'] = $tempat->trf_kebersihan;
                $data['airkotor'] = $tempat->trf_airkotor;
                $data['lain'] = $tempat->trf_lain;

                $now = date("Y-m-d",strtotime(Carbon::now()));
                $check = date("Y-m-15",strtotime(Carbon::now()));

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

                $tagihan = Tagihan::where([['bln_tagihan', $periode],['kd_kontrol',$tempat->kd_kontrol]])->first();
                if($tagihan != NULL){
                    return response()->json(['exist' => "Data Tagihan $tempat->kd_kontrol Sudah Ada"]);    
                }
                return response()->json(['result' => $data]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Fetching Data Failed']);
            }
        }
    }

    public function neraca(Request $request){
        if($request->ajax()){
            $data = Neraca::orderBy('id','desc');
            return DataTables::of($data)
                ->editColumn('debit', function ($data) {
                    if ($data->debit == NULL) return '';
                    else return number_format($data->debit);
                })
                ->editColumn('kredit', function ($data) {
                    if ($data->kredit == NULL) return '';
                    else return number_format($data->kredit);
                })
                ->editColumn('saldo', function ($data) {
                    if ($data->saldo == NULL) return '';
                    else return number_format($data->saldo);
                })
                ->editColumn('sisa', function ($data) {
                    if ($data->sisa == NULL) return '';
                    else return number_format($data->sisa);
                })
                ->editColumn('created_at', function ($data) {
                    return [
                        'display' => $data->created_at->format('d-m-Y H:i:s'),
                        'timestamp' => $data->created_at->timestamp
                    ];
                    })
                ->make(true);
        }
        return view('tagihan.neraca');
    }

    public function neracaStore(Request $request){
        
    }

    public function tempat(Request $request){
        $time = strtotime(Carbon::now());
        $now  = date("Y", $time);

        if($request->tempat == 'listrik'){
            $dataset = TempatUsaha::select('kd_kontrol')->where('trf_listrik','!=',NULL)->orderBy('kd_kontrol','asc')->get();
            $status  = 'Listrik';
        }
        else{
            $dataset = TempatUsaha::select('kd_kontrol')->where('trf_airbersih','!=',NULL)->orderBy('kd_kontrol','asc')->get();
            $status  = 'Air Bersih';
        }

        return view('tagihan.tempat',[
            'dataset' => $dataset,
            'now'     => $now,
            'status'  => $status
        ]);
    }
}
