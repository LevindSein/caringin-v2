<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;
use Validator;
use Exception;
use Artisan;

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
use App\Models\Pedagang;

use Carbon\Carbon;
use App\Models\Carbonet;

class TagihanController extends Controller
{
    public function __construct()
    {
        $this->middleware('tagihan');
    }

    public function index(Request $request)
    {
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

        if(Session::get('role') == 'admin'){
            $wherein = Session::get('otoritas')->otoritas;
            $blok = Blok::select('nama')->whereIn('nama',$wherein)->orderBy('nama')->get();
        }
        else{
            $blok = Blok::select('nama')->orderBy('nama')->get();
        }

        if(request()->ajax())
        {
            if(Session::get('role') == 'admin'){
                $wherein = Session::get('otoritas')->otoritas;
                $data = Tagihan::where('bln_tagihan',Session::get('periodetagihan'))->whereIn('blok',$wherein);
            }
            else{
                $data = Tagihan::where('bln_tagihan',Session::get('periodetagihan'));
            }
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master' || Session::get('role') == 'admin'){
                        if($data->stt_publish === 0){
                            if(Session::get('role') == 'master' || Session::get('otoritas')->tagihan){
                                if($data->review === 0)
                                    $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                                else
                                    $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#000000;"></i></a>';
                                $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                                $button .= '&nbsp;&nbsp;<a type="button" title="Publish" name="unpublish" id="'.$data->id.'" class="unpublish"><i class="fas fa-check-circle" style="color:#1cc88a;"></i></a>';
                            }
                            else if(Session::get('otoritas')->publish && Session::get('otoritas')->tagihan == false){
                                $hasil = number_format($data->ttl_tagihan);
                                $warna = max($data->warna_airbersih, $data->warna_listrik);        
                                if($warna == 1 || $warna == 2)
                                    $button = '<a type="button" title="Report Checking" name="report" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="report"><i class="fas fa-bell" style="color:#c4b71f;"></i></a>';
                                else if($warna == 3)
                                    $button = '<a type="button" title="Report Checking" name="report" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="report"><i class="fas fa-bell" style="color:#e74a3b;"></i></a>';
                                else
                                    $button = '<a type="button" title="Report Checking" name="report" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="report"><i class="fas fa-bell" style="color:#172b4d;"></i></a>';
                            }
                        }
                        else{
                            if(Session::get('role') == 'master')
                                $button = '<button type="button" title="Cancel Publish" name="unpublish" id="'.$data->id.'" class="unpublish btn btn-sm btn-danger">Cancel</button>';
                            else
                                $button = '<span style="color:#1cc88a;">Published</span>';
                        }
                    }
                    else{
                        if($data->stt_publish === 0){
                            $button = '<span style="color:#e74a3b;">Unpublished</span>';
                        }
                        else{
                            $button = '<span style="color:#1cc88a;">Published</span>';
                        }
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
                        else if($warna == 3)
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
            'blok'    => $blok,
        ]);
    }

    public function initiate(){
        try{
            $data = array();
            
            //Sinkronisasi
            $now = date("Y-m-d",strtotime(Carbon::now()));
            $check = date("Y-m-23",strtotime(Carbon::now()));
            if($now >= $check)
                $data['status'] = true;
            else
                $data['status'] = false;

            $carbon = Carbon::now();
            $carbon = strtotime($carbon);
            $date = date('Y-m-01',$carbon);
            $tanggal = new Carbonet($date, 1);
            $sync = Sinkronisasi::where('sinkron',$tanggal)->first();
            if($sync == NULL)
                $data['sync_text'] = "Synchronize";
            else
                $data['sync_text'] = "Unsynchronize";
                
            $data['sync'] = "$tanggal";

            $periode = '';
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
            $data['periode'] = IndoDate::bulan($periode, " ");

            return response()->json(['result' => $data]);
        }
        catch(\Exception $e){
            return response()->json(['errors' => $e]);
        }
    }

    public function synchronize($tanggal){
        if(request()->ajax()){
            try{
                Artisan::call('cron:tagihan');
                return response()->json(['success' => "Berhasil Melakukan Sinkronisasi"]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => "Gagal Melakukan Sinkronisasi"]);
            }
        }
    }

    public function unsynchronize($tanggal){
        if(request()->ajax()){
            try{
                Artisan::call('cron:awalmeter');
                Tagihan::where([['tgl_tagihan',$tanggal],['stt_bayar',0]])->delete();
                Sinkronisasi::where('sinkron',$tanggal)->delete();
                return response()->json(['success' => "Berhasil Membatalkan Tagihan"]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => "Gagal Membatalkan Tagihan"]);
            }
        }
    }

    public function badge(){
        $data = array();
        if(Session::get('role') == 'admin'){
            $wherein = Session::get('otoritas')->otoritas;
            $listrik = Tagihan::where([['tagihan.stt_listrik',0],['tempat_usaha.trf_listrik',1]])
            ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
            ->whereIn('tagihan.blok',$wherein)
            ->count();

            $air = Tagihan::where([['tagihan.stt_airbersih',0],['tempat_usaha.trf_airbersih',1]])
            ->leftJoin('tempat_usaha','tagihan.kd_kontrol','=','tempat_usaha.kd_kontrol')
            ->whereIn('tagihan.blok',$wherein)
            ->count();
        }
        else{
            $listrik = Tagihan::where('stt_listrik',0)
            ->count();
            $air = Tagihan::where('stt_airbersih',0)
            ->count();
        }

        $data['listrik'] = $listrik;
        $data['air']     = $air;

        return response()->json(['result' => $data]);
    }

    public function destroyEdit($id){
        if(request()->ajax()){
            $data = Tagihan::find($id);
            return response()->json(['result' => $data]);
        }
    }
    
    public function destroy(Request $request,$id){
        if(request()->ajax()){
            try{
                $data = Tagihan::findOrFail($id);
                
                $hapus = Penghapusan::find($data->id);
                if($hapus == NULL){
                    $hapus       = new Penghapusan;
                }
                $hapus->id   = $data->id;
                $hapus->no_faktur   = $data->no_faktur;
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
                $hapus->stt_prabayar = $data->stt_prabayar;
                $hapus->jml_alamat = $data->jml_alamat;
                $hapus->no_alamat = $data->no_alamat;
                $hapus->ket = $data->ket;
                $hapus->via_tambah = $data->via_tambah;
                $hapus->stt_publish = $data->stt_publish;
                $hapus->via_publish = $data->via_publish;
                $hapus->warna_airbersih = $data->warna_airbersih;
                $hapus->warna_listrik = $data->warna_listrik;
                $hapus->review = $data->review;
                $hapus->reviewer = $data->reviewer;
                $hapus->lok_tempat = $data->lok_tempat;
                $hapus->tgl_hapus = date("Y-m-d",strtotime(Carbon::now()));
                $hapus->via_hapus = Session::get('username');

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

                    $hapus->den_airbersih   = $data->den_airbersih;
                    $data->den_airbersih    = 0;

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

                $data = Tagihan::find($id);
                if($data->ttl_tagihan == 0)
                    $data->delete();
                    
                return response()->json(['success' => 'Data telah dihapus.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data gagal dihapus.']);
            }
        }
    }

    public function penghapusan(){
        if(request()->ajax())
        {
            if(Session::get('role') == 'admin'){
                $wherein = Session::get('otoritas')->otoritas;
                $data = Penghapusan::whereIn('blok',$wherein)->orderBy('kd_kontrol','asc');
            }
            else{
                $data = Penghapusan::orderBy('kd_kontrol','asc');
            }
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master' || Session::get('role') == 'admin'){
                        if(Session::get('role') == 'master' || Session::get('otoritas')->tagihan){
                            $button = '<a type="button" title="Restore" name="restore" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="restore"><i class="fas fa-undo" style="color:#e74a3b;"></i></a>';
                        }
                        else if(Session::get('otoritas')->publish && Session::get('otoritas')->tagihan == false){
                            $button = '<span style="color:#e74a3b;"><i class="fas fa-ban"></i></span>';
                        }
                    }
                    else{
                        $button = '<span style="color:#e74a3b;"><i class="fas fa-ban"></i></span>';
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
                        else if($warna == 3)
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
        return view('tagihan.penghapusan');
    }

    public function penghapusanRestore($id){
        if(request()->ajax()){
            try{
                $data  = Tagihan::find($id);
                $hapus = Penghapusan::find($id);
                if($data == NULL){
                    $data = new Tagihan;
                    $data->id = $hapus->id;
                    $data->no_faktur = $hapus->faktur;
                    $data->nama = $hapus->nama; 
                    $data->blok = $hapus->blok;
                    $data->kd_kontrol = $hapus->kd_kontrol; 
                    $data->bln_pakai = $hapus->bln_pakai;
                    $data->tgl_tagihan = $hapus->tgl_tagihan;
                    $data->bln_tagihan = $hapus->bln_tagihan;
                    $data->thn_tagihan = $hapus->thn_tagihan;
                    $data->tgl_expired = $hapus->tgl_expired;
                    $data->stt_lunas = $hapus->stt_lunas;
                    $data->stt_bayar = $hapus->stt_bayar;
                    $data->stt_prabayar = $hapus->stt_prabayar;
                    $data->awal_airbersih = $hapus->awal_airbersih;
                    $data->akhir_airbersih = $hapus->akhir_airbersih;
                    $data->pakai_airbersih = $hapus->pakai_airbersih;
                    $data->byr_airbersih = $hapus->byr_airbersih;
                    $data->pemeliharaan_airbersih = $hapus->pemeliharaan_airbersih;
                    $data->beban_airbersih = $hapus->beban_airbersih;
                    $data->arkot_airbersih = $hapus->arkot_airbersih;
                    $data->sub_airbersih = $hapus->sub_airbersih;
                    $data->dis_airbersih = $hapus->dis_airbersih;
                    $data->ttl_airbersih = $hapus->ttl_airbersih;
                    $data->rea_airbersih = $hapus->rea_airbersih;
                    $data->sel_airbersih = $hapus->sel_airbersih;
                    $data->den_airbersih = $hapus->den_airbersih;
                    $data->daya_listrik = $hapus->daya_listrik;
                    $data->daya_listrik = $hapus->daya_listrik;
                    $data->awal_listrik = $hapus->awal_listrik;
                    $data->akhir_listrik = $hapus->akhir_listrik;
                    $data->pakai_listrik = $hapus->pakai_listrik;
                    $data->byr_listrik = $hapus->byr_listrik;
                    $data->rekmin_listrik = $hapus->rekmin_listrik;
                    $data->blok1_listrik = $hapus->blok1_listrik;
                    $data->blok2_listrik = $hapus->blok2_listrik;
                    $data->beban_listrik = $hapus->beban_listrik;
                    $data->bpju_listrik = $hapus->bpju_listrik;
                    $data->sub_listrik = $hapus->sub_listrik;
                    $data->dis_listrik = $hapus->dis_listrik;
                    $data->ttl_listrik = $hapus->ttl_listrik;
                    $data->rea_listrik = $hapus->rea_listrik;
                    $data->sel_listrik = $hapus->sel_listrik;
                    $data->den_listrik = $hapus->den_listrik;
                    $data->jml_alamat = $hapus->jml_alamat;
                    $data->no_alamat = $hapus->no_alamat;
                    $data->sub_keamananipk = $hapus->sub_keamananipk;
                    $data->dis_keamananipk = $hapus->dis_keamananipk;
                    $data->ttl_keamananipk = $hapus->ttl_keamananipk;
                    $data->ttl_keamanan = $hapus->ttl_keamanan;
                    $data->ttl_ipk = $hapus->ttl_ipk;
                    $data->rea_keamananipk = $hapus->rea_keamananipk;
                    $data->sel_keamananipk = $hapus->sel_keamananipk;
                    $data->sub_kebersihan = $hapus->sub_kebersihan;
                    $data->dis_kebersihan = $hapus->dis_kebersihan;
                    $data->ttl_kebersihan = $hapus->ttl_kebersihan;
                    $data->rea_kebersihan = $hapus->rea_kebersihan;
                    $data->sel_kebersihan = $hapus->sel_kebersihan;
                    $data->ttl_airkotor = $hapus->ttl_airkotor;
                    $data->rea_airkotor = $hapus->rea_airkotor;
                    $data->sel_airkotor = $hapus->sel_airkotor;
                    $data->ttl_lain = $hapus->ttl_lain;
                    $data->rea_lain = $hapus->rea_lain;
                    $data->sel_lain = $hapus->sel_lain;
                    $data->sub_tagihan = $hapus->sub_tagihan;
                    $data->dis_tagihan = $hapus->dis_tagihan;
                    $data->ttl_tagihan = $hapus->ttl_tagihan;
                    $data->rea_tagihan = $hapus->rea_tagihan;
                    $data->sel_tagihan = $hapus->sel_tagihan;
                    $data->den_tagihan = $hapus->den_tagihan;
                    $data->stt_denda = $hapus->stt_denda;
                    $data->stt_kebersihan = $hapus->stt_kebersihan;
                    $data->stt_keamananipk = $hapus->stt_keamananipk;
                    $data->stt_listrik = $hapus->stt_listrik;
                    $data->stt_airbersih = $hapus->stt_airbersih;
                    $data->stt_airkotor = $hapus->stt_airkotor;
                    $data->stt_lain = $hapus->stt_lain;
                    $data->ket = $hapus->ket;
                    $data->via_tambah = $hapus->via_tambah;
                    $data->stt_publish = $hapus->stt_publish;
                    $data->via_publish = $hapus->via_publish;
                    $data->warna_airbersih = $hapus->warna_airbersih;
                    $data->warna_listrik = $hapus->warna_listrik;
                    $data->review = $hapus->review;
                    $data->reviewer = $hapus->reviewer;
                    $data->lok_tempat = $hapus->lok_tempat;
                    $data->save();
                    $hapus->delete();
                }
                else{
                    if($hapus->stt_listrik !== NULL){
                        $data->daya_listrik  = $hapus->daya_listrik;
                        
                        $data->awal_listrik  = $hapus->awal_listrik;
    
                        $data->akhir_listrik = $hapus->akhir_listrik;
    
                        $data->pakai_listrik = $hapus->pakai_listrik;
    
                        $data->byr_listrik   = $hapus->byr_listrik;
    
                        $data->rekmin_listrik = $hapus->rekmin_listrik;
    
                        $data->blok1_listrik  = $hapus->blok1_listrik;
    
                        $data->blok2_listrik  = $hapus->blok2_listrik;
    
                        $data->beban_listrik = $hapus->beban_listrik;
    
                        $data->bpju_listrik  = $hapus->bpju_listrik;
    
                        $data->sub_listrik   = $hapus->sub_listrik;
    
                        $data->dis_listrik   = $hapus->dis_listrik;
    
                        $data->ttl_listrik   = $hapus->ttl_listrik;
    
                        $data->rea_listrik   = $hapus->rea_listrik;
    
                        $data->sel_listrik   = $hapus->sel_listrik;
    
                        $data->den_listrik   = $hapus->den_listrik;
    
                        $data->stt_listrik   = $hapus->stt_listrik;
                    }
                    
                    if($hapus->stt_airbersih !== NULL){
                        $data->awal_airbersih = $hapus->awal_airbersih;
    
                        $data->akhir_airbersih = $hapus->akhir_airbersih;
    
                        $data->pakai_airbersih = $hapus->pakai_airbersih;
    
                        $data->byr_airbersih = $hapus->byr_airbersih;
    
                        $data->pemeliharaan_airbersih = $hapus->pemeliharaan_airbersih;
    
                        $data->beban_airbersih = $hapus->beban_airbersih;
    
                        $data->arkot_airbersih = $hapus->arkot_airbersih;
    
                        $data->sub_airbersih = $hapus->sub_airbersih;
    
                        $data->dis_airbersih = $hapus->dis_airbersih;
    
                        $data->ttl_airbersih = $hapus->ttl_airbersih;
    
                        $data->rea_airbersih = $hapus->rea_airbersih;
    
                        $data->sel_airbersih = $hapus->sel_airbersih;
    
                        $data->den_airbersih   = $hapus->den_airbersih;
    
                        $data->stt_airbersih = $hapus->stt_airbersih;
                    }

                    if($hapus->stt_keamananipk !== NULL){
                        $data->sub_keamananipk = $hapus->sub_keamananipk;

                        $data->dis_keamananipk = $hapus->dis_keamananipk;

                        $data->ttl_keamananipk = $hapus->ttl_keamananipk;

                        $data->ttl_keamanan = $hapus->ttl_keamanan;

                        $data->ttl_ipk = $hapus->ttl_ipk;

                        $data->rea_keamananipk = $hapus->rea_keamananipk;

                        $data->sel_keamananipk = $hapus->sel_keamananipk;

                        $data->stt_keamananipk = $hapus->stt_keamananipk;
                    }

                    if($hapus->stt_kebersihan !== NULL){
                        $data->sub_kebersihan = $hapus->sub_kebersihan;

                        $data->dis_kebersihan = $hapus->dis_kebersihan;

                        $data->ttl_kebersihan = $hapus->ttl_kebersihan;

                        $data->rea_kebersihan = $hapus->rea_kebersihan;

                        $data->sel_kebersihan = $hapus->sel_kebersihan;

                        $data->stt_kebersihan = $hapus->stt_kebersihan;
                    }

                    if($hapus->stt_airkotor !== NULL){
                        $data->ttl_airkotor = $hapus->ttl_airkotor;

                        $data->rea_airkotor = $hapus->rea_airkotor;

                        $data->sel_airkotor = $hapus->sel_airkotor;

                        $data->stt_airkotor = $hapus->stt_airkotor;
                    }

                    if($hapus->stt_lain !== NULL){
                        $data->ttl_lain = $hapus->ttl_lain;

                        $data->rea_lain = $hapus->rea_lain;

                        $data->sel_lain = $hapus->sel_lain;

                        $data->stt_lain = $hapus->stt_lain;
                    }

                    $data->save();
                    $hapus->delete();

                    Tagihan::totalTagihan($id);
                }
                return response()->json(['success' => 'Data telah direstorasi.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => $e]);
            }
        }
    }

    public function unpublish($id){
        if(request()->ajax()){
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

    public function periode(Request $request){
        $periode = $request->tahun."-".$request->bulan;
        return redirect()->route('tagihan', ['periode' => $periode]);
    }

    public function notifEdit($id){
        if(request()->ajax()){
            $data = Tagihan::find($id);
            return response()->json(['result' => $data]);
        }
    }

    public function notif(Request $request, $id){
        if(request()->ajax()){
            try{
                $data = Tagihan::find($id);
                $ket = '';
                if(empty($request->notifListrik) == FALSE){
                    $ket .= "Listrik, ";
                }
                if(empty($request->notifAirBersih) == FALSE){
                    $ket .= "Air Bersih, ";
                }
                if(empty($request->notifKeamananIpk) == FALSE){
                    $ket .= "Keamanan IPK, ";
                }
                if(empty($request->notifKebersihan) == FALSE){
                    $ket .= "Kebersihan, ";
                }
                if(empty($request->notifAirKotor) == FALSE){
                    $ket .= "Air Kotor, ";
                }
                if(empty($request->notifLain) == FALSE){
                    $ket .= "Lainnya, ";
                }

                $ket = rtrim($ket, ", ");

                $data->ket = $ket;
                $data->reviewer = Session::get('username');
                
                //1 = Verified
                //0 = Checking
                //2 = Edited
                $data->review = 0;
                $data->save();
                return response()->json(['success' => 'Notifikasi Terkirim']);
            }
            catch(Exception $e){
                return response()->json(['errors' => 'Notifikasi Gagal dikirim']);
            }
        }
    }

    public function publish(Request $request){
        if(request()->ajax()){
            $data = array();
            $periode = $request->publish_tahun."-".$request->publish_bulan;
            $action = $request->publish_action;

            try{
                $i = 0;
                $banyak  = Tagihan::where('bln_tagihan',$periode)->count();
                if($action == 'publish'){
                    $status  = 'publish';
                    $tagihan = Tagihan::where([['bln_tagihan',$periode],['stt_publish',0]])->get();
                    foreach($tagihan as $d){
                        $d->stt_publish = 1;
                        $d->save();
                        $i++;
                    }
                }
                else{
                    $status = 'unpublish';
                    $tagihan = Tagihan::where([['bln_tagihan',$periode],['stt_publish',1]])->get();
                    foreach($tagihan as $d){
                        $pembayaran = Pembayaran::where('id_tagihan',$d->id)->first();
                        if($pembayaran == NULL){
                            $hasil = 0;
                            $i++;
                            $d->stt_publish = $hasil;
                            $d->save();
                        }
                    }
                } 
                $data['status']  = true;
                $data['message'] = "Berhasil melakukan $status $i dari $banyak data";
                $data['periode'] = $periode;
            }
            catch(\Exception $e){
                $data['status']  = false;
                $data['message'] = "Gagal Mengambil Data";
                $data['periode'] = $periode;
            }
            
            return response()->json(['result' => $data]);
        }
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

    public function print(){
        Artisan::call('cron:akhirmeter');
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

    public function pemberitahuan(Request $request){
        $blok = $request->blokPemberitahuan;
        $date = date("Y-m-d",strtotime(Carbon::now()));
        $check = date("Y-m-22",strtotime(Carbon::now()));

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

    public function pembayaran(Request $request){
        $blok = $request->blokPembayaran;
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

    public function listrik(Request $request){
        $blok = $request->tagihan_blok;

        if(Session::get('role') == 'admin'){
            if (!in_array($blok, Session::get('otoritas')->otoritas)) {
                return redirect()->route('tagihan');
            }
        }

        $tagihan = Tagihan::where([['stt_listrik',0],['stt_publish',0],['blok',$blok]])->orderBy('kd_kontrol','asc')->first();

        if($tagihan == NULL){
            return redirect()->route('tagihan');
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
                return redirect()->route('tagihan');
            }
        }

        $tagihan = Tagihan::where([['stt_airbersih',0],['stt_publish',0],['blok',$blok]])->orderBy('kd_kontrol','asc')->first();

        if($tagihan == NULL){
            return redirect()->route('tagihan');
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
        $tagihan->save();

        $nama = Tagihan::find($request->hidden_id);
        $nama->nama = $request->nama;
        $nama->save();

        $this->total($request->hidden_id);

        return redirect()->route('airbersih',['tagihan_blok'=>$blok]);
    }

    public function refreshTarif(Request $request){
        if(request()->ajax()){
            $data = array();
            $periode = $request->refresh_tahun."-".$request->refresh_bulan;
            $action = $request->refresh;

            try{
                if($action == 'listrik'){
                    $banyak  = Tagihan::where([['bln_tagihan',$periode],['stt_bayar',0],['stt_listrik',1]])->count();
                    $status = "Listrik";
                    $i = Tagihan::refreshListrik($periode);
                }
                else{
                    $banyak  = Tagihan::where([['bln_tagihan',$periode],['stt_bayar',0],['stt_airbersih',1]])->count();
                    $status = "Air Bersih";
                    $i = Tagihan::refreshAirBersih($periode);
                } 
                $data['status']  = true;
                $data['message'] = "Berhasil melakukan pengitungan ulang tarif $status $i dari $banyak data";
                $data['periode'] = $periode;
            }
            catch(\Exception $e){
                $data['status']  = false;
                $data['message'] = "Gagal Mengambil Data";
                $data['periode'] = $periode;
            }
            
            return response()->json(['result' => $data]);
        }
    }

    public function checkManual(Request $request){
        if(request()->ajax()){
            $periode = $request->manual_tahun."-".$request->manual_bulan;

            $tempat = TempatUsaha::find($request->kontrol_manual);
            
            $tagihan = Tagihan::where([['bln_tagihan', $periode],['kd_kontrol',$tempat->kd_kontrol]])->first();
            if($tagihan != NULL){
                $periode = IndoDate::bulan($periode, " ");
                return response()->json(['errors' => "Data Tagihan $tempat->kd_kontrol Sudah Ada pada periode $periode"]);
            }
            else{
                $data = array();
                $pedagang = Pedagang::find($tempat->id_pengguna);
                $data['nama']    = $pedagang->nama;
                $data['kontrol'] = $tempat->kd_kontrol;
                $data['periode'] = IndoDate::bulan($periode, " ");
                if($tempat->trf_listrik !== NULL)
                    $data['listrik'] = true;
                else
                    $data['listrik'] = false;
                
                if($tempat->trf_airbersih !== NULL)
                    $data['airbersih'] = true;
                else
                    $data['asirbersih'] = false;
                
                if($tempat->trf_keamananipk !== NULL)
                    $data['keamananipk'] = true;
                else
                    $data['keamananipk'] = false;

                if($tempat->trf_kebersihan !== NULL)
                    $data['kebersihan'] = true;
                else
                    $data['kebersihan'] = false;

                if($tempat->trf_airkotor !== NULL)
                    $data['airkotor'] = true;
                else
                    $data['airkotor'] = false;

                if($tempat->trf_lain !== NULL)
                    $data['lain'] = true;
                else
                    $data['lain'] = false;

                return response()->json(['result' => $data]); 
            }
        }
    }
    public function manual(Request $request){
        if(request()->ajax()){
            try{
                return response()->json(['success' => "Data Berhasil Ditambah"]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => "Data Gagal Ditambah"]);
            }
        }
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
}
