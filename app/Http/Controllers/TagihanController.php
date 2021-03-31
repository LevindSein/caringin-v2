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

        if($request->ajax())
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
            return response()->json(['result' => $e]);
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
                Artisan::call('cron:alatmeter');
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

    }

    public function penghapusanRestore($id){

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
}
