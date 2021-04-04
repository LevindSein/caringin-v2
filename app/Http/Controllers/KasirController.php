<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

use App\Models\Kasir;
use App\Models\Tagihan;
use App\Models\Struk70mm;
use App\Models\Struk80mm;
use App\Models\Pembayaran;
use App\Models\IndoDate;
use App\Models\User;
use App\Models\Harian;
use App\Models\Item;
use App\Models\StrukPembayaran;
use App\Models\Perkiraan;

use App\Models\TempatUsaha;
use App\Models\Sinkronisasi;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\EscposImage;

use Jenssegers\Agent\Agent;

use DataTables;
use Validator;
use Exception;

use Carbon\Carbon;

class KasirController extends Controller
{
    public function __construct()
    {
        $this->middleware('kasir');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(request()->ajax()){
            $data = Tagihan::select('kd_kontrol')
            ->groupBy('kd_kontrol')
            ->where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]]);
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<button title="Bayar" name="bayar" id="'.$data->kd_kontrol.'" nama="'.$data->kd_kontrol.'" class="bayar btn btn-sm btn-success">Bayar</button>';
                    return $button;
                })
                ->addColumn('pengguna', function($data){
                    $pengguna = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('id_pengguna')->first();
                    if($pengguna != NULL){
                        return User::find($pengguna->id_pengguna)->nama;
                    }
                    else{
                        return 'Unknown';
                    }
                })
                ->addColumn('lokasi', function($data){
                    $lokasi = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('lok_tempat')->first();
                    if($lokasi != NULL){
                        return $lokasi->lok_tempat;
                    }
                    else{
                        return '';
                    }
                })
                ->addColumn('tagihan', function($data){
                    $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_lunas',0],['stt_publish',1]])
                    ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('kasir.bulanan.index',[
            'bulan'=>IndoDate::bulan(date("Y-m", strtotime(Carbon::now())),' '),
        ]);
    }

    public function restore(Request $request){
        if(request()->ajax()){
            $data = Pembayaran::select('ref','kd_kontrol')
            ->groupBy('kd_kontrol','ref')
            ->where([['tgl_bayar',date('Y-m-d',strtotime(Carbon::now()))],['id_kasir',Session::get('userId')]]);
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<button title="Restore" name="restore" id="'.$data->ref.'" nama="'.$data->kd_kontrol.'" class="restore btn btn-sm btn-danger">Restore</button>';
                    return $button;
                })
                ->addColumn('pengguna', function($data){
                    $pengguna = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('id_pengguna')->first();
                    if($pengguna != NULL){
                        return User::find($pengguna->id_pengguna)->nama;
                    }
                    else{
                        return 'Unknown';
                    }
                })
                ->addColumn('lokasi', function($data){
                    $lokasi = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('lok_tempat')->first();
                    if($lokasi != NULL){
                        return $lokasi->lok_tempat;
                    }
                    else{
                        return '';
                    }
                })
                ->addColumn('tagihan', function($data){
                    $tagihan = Pembayaran::where([['kd_kontrol',$data->kd_kontrol],['ref',$data->ref]])
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function struk(Request $request, $struk){
        if($struk == 'tagihan'){
            if(request()->ajax()){
                $data = StrukPembayaran::orderBy('updated_at','desc');
                return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button title="Cetak" name="cetak" id="'.$data->id.'" nama="'.$data->kd_kontrol.'" class="cetak btn btn-sm btn-info">Cetak</button>';
                        return $button;
                    })
                    ->editColumn('totalTagihan', function($data){
                        return number_format($data->totalTagihan);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
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

    public function rincian(Request $request, $kontrol){
        $bulan = date("Y-m", strtotime(Carbon::now()));

        if(request()->ajax()){
            $dataset = array();

            //Periode ini -----------------------------------------
            
            $data = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['bln_tagihan','>=',date('Y-m',strtotime(Carbon::now()))]])->orderBy('bln_tagihan','asc')->get();
            $dataset['listrik'] = 0;
            $dataset['airbersih'] = 0;
            $dataset['keamananipk'] = 0;
            $dataset['kebersihan'] = 0;
            $dataset['airkotor'] = 0;
            $dataset['lain'] = 0;
            
            $dataset['dylistrik'] = 0;
            $dataset['awlistrik'] = 0;
            $dataset['aklistrik'] = 0;
            $dataset['pklistrik'] = 0;
            
            $dataset['awairbersih'] = 0;
            $dataset['akairbersih'] = 0;
            $dataset['pkairbersih'] = 0;
            
            if($data != NULL){
                $listrik = 0;
                $denlistrik = 0;
                $airbersih = 0;
                $denairbersih = 0;
                $keamananipk = 0;
                $kebersihan = 0;
                $airkotor= 0;
                $lain = 0;
                foreach($data as $d){
                    $listrik = $listrik + $d->sel_listrik;
                    $denlistrik = $denlistrik + $d->den_listrik;
                    $dayalistrik = $d->daya_listrik;
                    $awallistrik = $d->awal_listrik;
                    $akhirlistrik = $d->akhir_listrik;
                    $pakailistrik = $d->pakai_listrik;
                    
                    $airbersih = $airbersih + $d->sel_airbersih;
                    $denairbersih = $denairbersih + $d->den_airbersih;
                    $awalairbersih = $d->awal_airbersih;
                    $akhirairbersih = $d->akhir_airbersih;
                    $pakaiairbersih = $d->pakai_airbersih;
                    
                    $keamananipk = $keamananipk + $d->sel_keamananipk;
    
                    $kebersihan = $kebersihan + $d->sel_kebersihan;
                    
                    $airkotor = $airkotor + $d->sel_airkotor;
    
                    $lain = $lain + $d->sel_lain;
                }
                
                if($listrik != 0 || $listrik != NULL){
                    $dataset['listrik'] = $listrik - $denlistrik;
                    $dataset['dylistrik'] = $dayalistrik;
                    $dataset['awlistrik'] = $awallistrik;
                    $dataset['aklistrik'] = $akhirlistrik;
                    $dataset['pklistrik'] = $pakailistrik;
                }
                if($airbersih != 0 || $airbersih != NULL){
                    $dataset['airbersih'] = $airbersih - $denairbersih;
                    $dataset['awairbersih'] = $awalairbersih;
                    $dataset['akairbersih'] = $akhirairbersih;
                    $dataset['pkairbersih'] = $pakaiairbersih;
                }
                if($keamananipk != 0 || $keamananipk != NULL)
                    $dataset['keamananipk'] = $keamananipk;
                if($kebersihan != 0 || $kebersihan != NULL)
                    $dataset['kebersihan'] = $kebersihan;
                if($airkotor != 0 || $airkotor != NULL)
                    $dataset['airkotor'] = $airkotor;
                if($lain != 0 || $lain != NULL)
                    $dataset['lain'] = $lain;
            }
            
            //Periode Lalu ----------------------------------------

            $data = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1],['stt_denda','!=',NULL],['bln_tagihan','<',date('Y-m',strtotime(Carbon::now()))]])->get();
            $dataset['tunglistrik'] = 0;
            $dataset['tungairbersih'] = 0;
            $dataset['tungkeamananipk'] = 0;
            $dataset['tungkebersihan'] = 0;
            $dataset['tungairkotor'] = 0;
            $dataset['tunglain'] = 0;
            if($data != NULL){
                $listrik = 0;
                $denlistrik = 0;
                $airbersih = 0;
                $denairbersih = 0;
                $keamananipk = 0;
                $kebersihan = 0;
                $airkotor = 0;
                $lain = 0;
                foreach($data as $d){
                    $listrik    = $listrik + $d->sel_listrik;
                    $denlistrik = $denlistrik + $d->den_listrik;
                    
                    $airbersih    = $airbersih + $d->sel_airbersih;
                    $denairbersih = $denairbersih + $d->den_airbersih;

                    $keamananipk = $keamananipk + $d->sel_keamananipk;

                    $kebersihan = $kebersihan + $d->sel_kebersihan;
                    
                    $airkotor = $airkotor + $d->sel_airkotor;
                    
                    $lain = $lain + $d->sel_lain;
                }

                if($listrik != 0 || $listrik != NULL)
                    $dataset['tunglistrik'] = $listrik - $denlistrik;
                
                if($airbersih != 0 || $airbersih != NULL)
                    $dataset['tungairbersih'] = $airbersih - $denairbersih;

                if($keamananipk != 0 || $keamananipk != NULL)
                    $dataset['tungkeamananipk'] = $keamananipk;

                if($kebersihan != 0 || $kebersihan != NULL)
                    $dataset['tungkebersihan'] = $kebersihan;

                if($airkotor != 0 || $airkotor != NULL)
                    $dataset['tungairkotor'] = $airkotor;

                if($lain != 0 || $lain != NULL)
                    $dataset['tunglain'] = $lain;
            }

            //Periode Ini + Lalu ----------------------------------

            $no_faktur = '';
            $data = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1]])->get();
            $dataset['denlistrik'] = 0;
            $dataset['denairbersih'] = 0;
            if($data != NULL){
                $listrik = 0;
                $airbersih = 0;
                foreach($data as $d){
                    if($d->sel_listrik > 0)
                        $listrik = $listrik + $d->den_listrik;
                    
                    if($d->sel_airbersih > 0)
                        $airbersih = $airbersih + $d->den_airbersih;

                    if($d->no_faktur === NULL){
                        $faktur = Sinkronisasi::where('sinkron', date('Y-m-01',strtotime(Carbon::now())))->first();
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
                        $d->no_faktur = $no_faktur;
                        $d->save();
                    }
                    else{
                        $no_faktur = $d->no_faktur;
                    }
                }

                if($listrik != 0 || $listrik != NULL)
                    $dataset['denlistrik'] = $listrik;

                if($airbersih != 0 || $airbersih != NULL)
                    $dataset['denairbersih'] = $airbersih;
            }

            Session::put('fakturtagihan',$no_faktur);

            $data = TempatUsaha::where('kd_kontrol',$kontrol)->first();
            if($data != NULL){
                $dataset['pedagang'] = str_replace("/","-",User::find($data->id_pengguna)->nama);
                $dataset['los'] = $data->no_alamat;
                $dataset['lokasi'] = $data->lok_tempat;
            }
            else{
                $dataset['pedagang'] = '';
                $dataset['los'] = '';
                $dataset['lokasi'] = '';
            }
            $dataset['faktur'] = Crypt::encryptString($no_faktur);

            $dataset['ref'] = $this->referensi();

            return response()->json(['result' => $dataset]);
        }
    }

    public function referensi(){
        $ref = str_shuffle('ABCDEFGHJKMNPQRSTUVWXYZ');
        $ref = substr($ref,0,10);

        $pembayaran = Pembayaran::where('ref', $ref)->first();
        if($pembayaran != NULL){
            $this->referensi();
        }
        else{
            return $ref;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bayar = '';
        $tagihan = '';
        $data = array();
        try{
            if($request->totalTagihan == 0){
                return response()->json(['errors' => 'Transaksi 0 Tidak Dapat Dilakukan']);
            }

            //Pembayaran Kontan
            $id = $request->tempatId;
            $tagihan = Tagihan::where([['kd_kontrol',$id],['stt_lunas',0],['stt_publish',1]])->get();
            foreach($tagihan as $d){
                $tanggal = date("Y-m-d", strtotime(Carbon::now()));
                $bulan = date("Y-m", strtotime(Carbon::now()));
                $tahun = date("Y", strtotime(Carbon::now()));
    
                //--------------------------------------------------------------
                $pembayaran = new Pembayaran;
                $pembayaran->ref       = $request->ref;
                $pembayaran->tgl_bayar = $tanggal;
                $pembayaran->bln_bayar = $bulan;
                $pembayaran->thn_bayar = $tahun;
                $pembayaran->tgl_tagihan = $d->tgl_tagihan;
                $pembayaran->via_bayar = 'kasir';
                $pembayaran->id_kasir = Session::get('userId');
                $pembayaran->nama = Session::get('username');
                $pembayaran->blok = $d->blok;
                $pembayaran->kd_kontrol = $d->kd_kontrol;
                $pembayaran->pengguna = $d->nama;
                $pembayaran->id_tagihan = $d->id;
                $pembayaran->shift = Session::get('work');
                $pembayaran->no_faktur = Session::get('fakturtagihan');

                $total = 0;
                $selisih = $d->sel_tagihan;
                $diskon = 0;

                if(empty($request->checkListrik) == FALSE){
                    $pembayaran->byr_listrik = $d->sel_listrik;
                    $pembayaran->byr_denlistrik = $d->den_listrik;
                    $pembayaran->sel_listrik = 0;
                    $pembayaran->dis_listrik = $d->dis_listrik;

                    $total = $total + $pembayaran->byr_listrik;
                    $selisih = $selisih - $pembayaran->byr_listrik;
                    $diskon = $diskon + $pembayaran->dis_listrik;
                }
                else{
                    $pembayaran->byr_listrik = 0;
                    $pembayaran->byr_denlistrik = 0;
                    $pembayaran->sel_listrik = $d->sel_listrik;
                    $pembayaran->dis_listrik = 0;

                    $total = $total + $pembayaran->byr_listrik;
                    $selisih = $selisih - $pembayaran->byr_listrik;
                    $diskon = $diskon + $pembayaran->dis_listrik;
                }
                
                if(empty($request->checkAirBersih) == FALSE){
                    $pembayaran->byr_airbersih = $d->sel_airbersih;
                    $pembayaran->byr_denairbersih = $d->den_airbersih;
                    $pembayaran->sel_airbersih = 0;
                    $pembayaran->dis_airbersih = $d->dis_airbersih;
                    
                    $total = $total + $pembayaran->byr_airbersih;
                    $selisih = $selisih - $pembayaran->byr_airbersih;
                    $diskon = $diskon + $pembayaran->dis_airbersih;
                }
                else{
                    $pembayaran->byr_airbersih = 0;
                    $pembayaran->byr_denairbersih = 0;
                    $pembayaran->sel_airbersih = $d->sel_airbersih;
                    $pembayaran->dis_airbersih = 0;

                    $total = $total + $pembayaran->byr_airbersih;
                    $selisih = $selisih - $pembayaran->byr_airbersih;
                    $diskon = $diskon + $pembayaran->dis_airbersih;
                }
                
                if(empty($request->checkKeamananIpk) == FALSE){
                    $pembayaran->byr_keamanan = $d->ttl_keamanan;
                    $pembayaran->byr_ipk      = $d->ttl_ipk;
                    $pembayaran->byr_keamananipk = $d->sel_keamananipk;
                    $pembayaran->sel_keamananipk = 0;
                    $pembayaran->dis_keamananipk = $d->dis_keamananipk;
                    
                    $total = $total + $pembayaran->byr_keamananipk;
                    $selisih = $selisih - $pembayaran->byr_keamananipk;
                    $diskon = $diskon + $pembayaran->dis_keamananipk;
                }
                else{
                    $pembayaran->byr_keamanan = 0;
                    $pembayaran->byr_ipk      = 0;
                    $pembayaran->byr_keamananipk = 0;
                    $pembayaran->sel_keamananipk = $d->sel_keamananipk;
                    $pembayaran->dis_keamananipk = 0;

                    $total = $total + $pembayaran->byr_keamananipk;
                    $selisih = $selisih - $pembayaran->byr_keamananipk;
                    $diskon = $diskon + $pembayaran->dis_keamananipk;
                }

                if(empty($request->checkKebersihan) == FALSE){
                    $pembayaran->byr_kebersihan = $d->sel_kebersihan;
                    $pembayaran->sel_kebersihan = 0;
                    $pembayaran->dis_kebersihan = $d->dis_kebersihan;
                    
                    $total = $total + $pembayaran->byr_kebersihan;
                    $selisih = $selisih - $pembayaran->byr_kebersihan;
                    $diskon = $diskon + $pembayaran->dis_kebersihan;
                }
                else{
                    $pembayaran->byr_kebersihan = 0;
                    $pembayaran->sel_kebersihan = $d->sel_kebersihan;
                    $pembayaran->dis_kebersihan = 0;

                    $total = $total + $pembayaran->byr_kebersihan;
                    $selisih = $selisih - $pembayaran->byr_kebersihan;
                    $diskon = $diskon + $pembayaran->dis_kebersihan;
                }

                if(empty($request->checkAirKotor) == FALSE){
                    $pembayaran->byr_airkotor = $d->sel_airkotor;
                    $pembayaran->sel_airkotor = 0;
                    
                    $total = $total + $pembayaran->byr_airkotor;
                    $selisih = $selisih - $pembayaran->byr_airkotor;
                }
                else{
                    $pembayaran->byr_airkotor = 0;
                    $pembayaran->sel_airkotor = $d->sel_airkotor;

                    $total = $total + $pembayaran->byr_airkotor;
                    $selisih = $selisih - $pembayaran->byr_airkotor;
                }

                if(empty($request->checkLain) == FALSE){
                    $pembayaran->byr_lain = $d->sel_lain;
                    $pembayaran->sel_lain = 0;
                    
                    $total = $total + $pembayaran->byr_lain;
                    $selisih = $selisih - $pembayaran->byr_lain;
                }
                else{
                    $pembayaran->byr_lain = 0;
                    $pembayaran->sel_lain = $d->sel_lain;

                    $total = $total + $pembayaran->byr_lain;
                    $selisih = $selisih - $pembayaran->byr_lain;
                }

                $pembayaran->sub_tagihan = $d->sub_tagihan;
                $pembayaran->diskon = $diskon;
                $pembayaran->ttl_tagihan = $d->ttl_tagihan;
                $pembayaran->realisasi = $total;
                $pembayaran->sel_tagihan = $selisih;
                $pembayaran->stt_denda = $d->stt_denda;
                $pembayaran->save();

                //-------------------------------------------------------------
                $total = 0;
                $selisih = $d->sel_tagihan;

                $data['checkAirBersih'] = FALSE;
                $data['checkListrik'] = FALSE;
                $data['checkKeamananIpk'] = FALSE;
                $data['checkKebersihan'] = FALSE;
                $data['checkAirKotor'] = FALSE;
                $data['checkLain'] = FALSE;

                if(empty($request->checkAirBersih) == FALSE){
                    $d->rea_airbersih = $d->ttl_airbersih;
                    $total = $total + $d->rea_airbersih;
                    $selisih = $selisih - $d->sel_airbersih;
                    $d->sel_airbersih = 0;
                    $data['checkAirBersih'] = TRUE;
                }

                if(empty($request->checkListrik) == FALSE){
                    $d->rea_listrik = $d->ttl_listrik;
                    $total = $total + $d->rea_listrik;
                    $selisih = $selisih - $d->sel_listrik;
                    $d->sel_listrik = 0;
                    $data['checkListrik'] = TRUE;
                }

                if(empty($request->checkKeamananIpk) == FALSE){
                    $d->rea_keamananipk = $d->ttl_keamananipk;
                    $total = $total + $d->rea_keamananipk;
                    $selisih = $selisih - $d->sel_keamananipk;
                    $d->sel_keamananipk = 0;
                    $data['checkKeamananIpk'] = TRUE;
                }

                if(empty($request->checkKebersihan) == FALSE){
                    $d->rea_kebersihan = $d->ttl_kebersihan;
                    $total = $total + $d->rea_kebersihan;
                    $selisih = $selisih - $d->sel_kebersihan;
                    $d->sel_kebersihan = 0;
                    $data['checkKebersihan'] = TRUE;
                }

                if(empty($request->checkAirKotor) == FALSE){
                    $d->rea_airkotor = $d->ttl_airkotor;
                    $total = $total + $d->rea_airkotor;
                    $selisih = $selisih - $d->sel_airkotor;
                    $d->sel_airkotor = 0;
                    $data['checkAirKotor'] = TRUE;
                }

                if(empty($request->checkLain) == FALSE){
                    $d->rea_lain = $d->ttl_lain;
                    $total = $total + $d->rea_lain;
                    $selisih = $selisih - $d->sel_lain;
                    $d->sel_lain = 0;
                    $data['checkLain'] = TRUE;
                }

                if($selisih == 0){
                    $d->stt_lunas = 1;
                    $d->stt_denda = 0;
                }

                if($total != 0){
                    $d->stt_bayar = 1;
                }
                $d->save();

                Tagihan::totalTagihan($d->id);
            }

            $struk = new StrukPembayaran();
            $struk->ref = $request->ref;
            $struk->tgl_bayar = date('Y-m-d',strtotime(Carbon::now()));
            $struk->bln_bayar = date('Y-m',strtotime(Carbon::now()));
            $struk->kd_kontrol = $request->tempatId;
            $struk->pedagang = $request->pedagang;
            $struk->los = $request->los;
            $struk->lokasi = $request->lokasi;
            $struk->nomor = Crypt::decryptString($request->faktur);

            $struk->taglistrik = $request->taglistrik;
            $struk->tagtunglistrik = $request->tagtunglistrik;
            $struk->tagdenlistrik = $request->tagdenlistrik;
            $struk->tagawlistrik = $request->tagawlistrik;
            $struk->tagaklistrik = $request->tagaklistrik;
            $struk->tagdylistrik = $request->tagdylistrik;
            $struk->tagpklistrik = $request->tagpklistrik;
            
            $struk->tagairbersih = $request->tagairbersih;
            $struk->tagtungairbersih = $request->tagtungairbersih;
            $struk->tagdenairbersih = $request->tagdenairbersih;
            $struk->tagawairbersih = $request->tagawairbersih;
            $struk->tagakairbersih = $request->tagakairbersih;
            $struk->tagpkairbersih = $request->tagpkairbersih;
            
            $struk->tagkeamananipk = $request->tagkeamananipk;
            $struk->tagtungkeamananipk = $request->tagtungkeamananipk;
            
            $struk->tagkebersihan = $request->tagkebersihan;
            $struk->tagtungkebersihan = $request->tagtungkebersihan;
            
            $struk->tagairkotor = $request->tagairkotor;
            $struk->tagtungairkotor = $request->tagtungairkotor;
            
            $struk->taglain = $request->taglain;
            $struk->tagtunglain = $request->tagtunglain;
            
            $struk->totalTagihan = $request->totalTagihan;
            
            $struk->bayar = date('d/m/Y H:i:s',strtotime(Carbon::now()));
            
            $struk->kasir = Session::get('username');
            $struk->save();

            $data['kd_kontrol'] = $request->tempatId;
            $data['pedagang'] = $request->pedagang;
            $data['los'] = $request->los;
            $data['lokasi'] = $request->lokasi;
            $data['faktur'] = $request->faktur;
            $data['ref'] = $request->ref;

            $data['taglistrik'] = $request->taglistrik;
            $data['tagtunglistrik'] = $request->tagtunglistrik;
            $data['tagdenlistrik'] = $request->tagdenlistrik;
            $data['tagawlistrik'] = $request->tagawlistrik;
            $data['tagaklistrik'] = $request->tagaklistrik;
            $data['tagdylistrik'] = $request->tagdylistrik;
            $data['tagpklistrik'] = $request->tagpklistrik;
            
            $data['tagairbersih'] = $request->tagairbersih;
            $data['tagtungairbersih'] = $request->tagtungairbersih;
            $data['tagdenairbersih'] = $request->tagdenairbersih;
            $data['tagawairbersih'] = $request->tagawairbersih;
            $data['tagakairbersih'] = $request->tagakairbersih;
            $data['tagpkairbersih'] = $request->tagpkairbersih;
            
            $data['tagkeamananipk'] = $request->tagkeamananipk;
            $data['tagtungkeamananipk'] = $request->tagtungkeamananipk;
            
            $data['tagkebersihan'] = $request->tagkebersihan;
            $data['tagtungkebersihan'] = $request->tagtungkebersihan;
            
            $data['tagairkotor'] = $request->tagairkotor;
            $data['tagtungairkotor'] = $request->tagtungairkotor;

            $data['taglain'] = $request->taglain;
            $data['tagtunglain'] = $request->tagtunglain;

            $data['totalTagihan'] = $request->totalTagihan;

            $data['status'] = 'success';

            return response()->json(['result' => $data]);
        } catch(\Exception $e){
            $data['status'] = 'error';
            return response()->json(['result' => $data]);
        }
    }

    public function bayar($objData){
        $json = json_decode($objData);
        $kontrol  = $json->kd_kontrol;
        $pedagang = $json->pedagang;
        $los = $json->los;
        $lokasi = $json->lokasi;
        $faktur = Crypt::decryptString($json->faktur);
        $ref = $json->ref;

        $listrik         = number_format($json->taglistrik);
        $tunglistrik     = number_format($json->tagtunglistrik);
        $denlistrik      = number_format($json->tagdenlistrik);
        $dayalistrik     = number_format($json->tagdylistrik);
        $awallistrik     = number_format($json->tagawlistrik);
        $akhirlistrik    = number_format($json->tagaklistrik);
        $pakailistrik    = number_format($json->tagpklistrik);
        
        $airbersih       = number_format($json->tagairbersih);
        $tungairbersih   = number_format($json->tagtungairbersih);
        $denairbersih    = number_format($json->tagdenairbersih);
        $awalairbersih   = number_format($json->tagawairbersih);
        $akhirairbersih  = number_format($json->tagakairbersih);
        $pakaiairbersih  = number_format($json->tagpkairbersih);

        $keamananipk     = number_format($json->tagkeamananipk);
        $tungkeamananipk = number_format($json->tagtungkeamananipk);
        
        $kebersihan      = number_format($json->tagkebersihan);
        $tungkebersihan  = number_format($json->tagtungkebersihan);
        
        $airkotor        = number_format($json->tagairkotor);
        $tungairkotor    = number_format($json->tagtungairkotor);
        
        $lain            = number_format($json->taglain);
        $tunglain        = number_format($json->tagtunglain);

        $total           = number_format($json->totalTagihan);

        $nama            = Session::get('username');

        $bulan           = IndoDate::bulanS(date('Y-m',strtotime(Carbon::now())), ' ');

        $dirfile = storage_path('app/public/logo_struk.png');
        $logo = EscposImage::load($dirfile,false);

        $profile   = CapabilityProfile::load("POS-5890");
        $connector = new RawbtPrintConnector();
        $printer   = new Printer($connector,$profile);
        $i = 1;
        try{
            if(Session::get('printer') == 'panda'){
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> setEmphasis(true);
                $printer -> text("Nama    : $pedagang\n");
                $printer -> text("Kontrol : $kontrol\n");
                $printer -> text("Los     : $los\n");
                if($lokasi != ''){
                    $printer -> text("Lokasi  : $lokasi\n");
                }
                $printer -> text("No.Ref  : $ref\n");
                $printer -> setEmphasis(false);
                $printer -> feed();
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("$bulan\n");
                $printer -> feed();
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk80mm("Items","Rp.",true, true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------");
                if($json->checkListrik){
                    $printer -> feed();
                    if($json->taglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Listrik",$listrik,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk80mm("Daya" ,$dayalistrik,false));
                        $printer -> text(new Struk80mm("Awal" ,$awallistrik,false));
                        $printer -> text(new Struk80mm("Akhir",$akhirlistrik,false));
                        $printer -> text(new Struk80mm("Pakai",$pakailistrik,false));
                        $i++;
                    }
                    if($json->tagtunglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Listrik",$tunglistrik,true));
                        $i++;
                    }
                    if($json->tagdenlistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Den.Listrik",$denlistrik,true));
                        $i++;
                    }
                }
                if($json->checkAirBersih){
                    $printer -> feed();
                    if($json->tagairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Air Bersih",$airbersih,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk80mm("Awal" ,$awalairbersih,false));
                        $printer -> text(new Struk80mm("Akhir",$akhirairbersih,false));
                        $printer -> text(new Struk80mm("Pakai",$pakaiairbersih,false));
                        $i++;
                    }
                    if($json->tagtungairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Air Bersih",$tungairbersih,true));
                        $i++;
                    }
                    if($json->tagdenairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Den.Air Bersih",$denairbersih,true));
                        $i++;
                    }
                }
                if($json->checkKeamananIpk){
                    $printer -> feed();
                    if($json->tagkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Keamanan IPK",$keamananipk,true));
                        $i++;
                    }
                    if($json->tagtungkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Keamanan IPK",$tungkeamananipk,true));
                        $i++;
                    }
                }
                if($json->checkKebersihan){
                    $printer -> feed();
                    if($json->tagkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Kebersihan",$kebersihan,true));
                        $i++;
                    }
                    if($json->tagtungkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Kebersihan",$tungkebersihan,true));
                        $i++;
                    }
                }
                if($json->checkAirKotor){
                    $printer -> feed();
                    if($json->tagairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Air Kotor",$airkotor,true));
                        $i++;
                    }
                    if($json->tagtungairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Air Kotor",$tungairkotor,true));
                        $i++;
                    }
                }
                if($json->checkLain){
                    $printer -> feed();
                    if($json->taglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Lain Lain",$lain,true));
                        $i++;
                    }
                    if($json->tagtunglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Lain Lain",$tunglain,true));
                        $i++;
                    }
                }
                $printer -> feed();
                
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk80mm("Total",$total,true,true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------\n");
                $printer -> text("Nomor : $faktur\n");
                $printer -> text("Dibayar pada ".date('d/m/Y H:i:s',strtotime(Carbon::now()))."\n");
                $printer -> text("Harap simpan tanda terima ini\nsebagai bukti pembayaran yang sah.\nTerimakasih.\nPembayaran sudah termasuk PPN\n");
                $printer -> text("Ksr : $nama\n");
                $printer -> feed();
            }
            else if(Session::get('printer') == 'androidpos'){

            }
            else{
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
                $printer -> setJustification(Printer::JUSTIFY_LEFT);
                $printer -> setEmphasis(true);
                $printer -> text("Nama    : $pedagang\n");
                $printer -> text("Kontrol : $kontrol\n");
                $printer -> text("Los     : $los\n");
                if($lokasi != ''){
                    $printer -> text("Lokasi  : $lokasi\n");
                }
                $printer -> text("No.Ref  : $ref\n");
                $printer -> setEmphasis(false);
                $printer -> feed();
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> text("$bulan\n");
                $printer -> feed();
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk70mm("Items","Rp.",true, true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------");
                if($json->checkListrik){
                    $printer -> feed();
                    if($json->taglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Listrik",$listrik,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Daya" ,$dayalistrik,false));
                        $printer -> text(new Struk70mm("Awal" ,$awallistrik,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirlistrik,false));
                        $printer -> text(new Struk70mm("Pakai",$pakailistrik,false));
                        $i++;
                    }
                    if($json->tagtunglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Listrik",$tunglistrik,true));
                        $i++;
                    }
                    if($json->tagdenlistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Listrik",$denlistrik,true));
                        $i++;
                    }
                }
                if($json->checkAirBersih){
                    $printer -> feed();
                    if($json->tagairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Bersih",$airbersih,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Awal" ,$awalairbersih,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirairbersih,false));
                        $printer -> text(new Struk70mm("Pakai",$pakaiairbersih,false));
                        $i++;
                    }
                    if($json->tagtungairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Bersih",$tungairbersih,true));
                        $i++;
                    }
                    if($json->tagdenairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Air Bersih",$denairbersih,true));
                        $i++;
                    }
                }
                if($json->checkKeamananIpk){
                    $printer -> feed();
                    if($json->tagkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Keamanan IPK",$keamananipk,true));
                        $i++;
                    }
                    if($json->tagtungkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Keamanan IPK",$tungkeamananipk,true));
                        $i++;
                    }
                }
                if($json->checkKebersihan){
                    $printer -> feed();
                    if($json->tagkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Kebersihan",$kebersihan,true));
                        $i++;
                    }
                    if($json->tagtungkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Kebersihan",$tungkebersihan,true));
                        $i++;
                    }
                }
                if($json->checkAirKotor){
                    $printer -> feed();
                    if($json->tagairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Kotor",$airkotor,true));
                        $i++;
                    }
                    if($json->tagtungairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Kotor",$tungairkotor,true));
                        $i++;
                    }
                }
                if($json->checkLain){
                    $printer -> feed();
                    if($json->taglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Lain Lain",$lain,true));
                        $i++;
                    }
                    if($json->tagtunglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Lain Lain",$tunglain,true));
                        $i++;
                    }
                }
                $printer -> feed();
                
                $printer -> setJustification(Printer::JUSTIFY_CENTER);
                $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                $printer -> text(new Struk70mm("Total",$total,true,true));
                $printer -> selectPrintMode();
                $printer -> setFont(Printer::FONT_B);
                $printer -> text("----------------------------------------\n");
                $printer -> text("Nomor : $faktur\n");
                $printer -> text("Dibayar pada ".date('d/m/Y H:i:s',strtotime(Carbon::now()))."\n");
                $printer -> text("Harap simpan tanda terima ini\nsebagai bukti pembayaran yang sah.\nTerimakasih.\nPembayaran sudah termasuk PPN\n");
                $printer -> text("Ksr : $nama\n");
                $printer -> feed();
                $printer -> cut();
            }
        }catch(\Exception $e){
            return response()->json(['status' => 'Transaksi Berhasil, Gagal Print Struk']);
        }finally{
            $printer->close();
        }
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

    }

    public function penerimaan(Request $request){
        $tanggal = $request->tanggal;
        $cetak   = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));
        $shift   = $request->shift;

        if($shift == 2){
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['id_kasir',Session::get('userId')]])->whereIn('shift', [1, 0])->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
        }
        else{
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['id_kasir',Session::get('userId')],['shift',$shift]])->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
        }

        $rekap       = array();
        $rek         = 0;
        $listrik     = 0;
        $denlistrik  = 0;
        $airbersih   = 0;
        $denairbersih= 0;
        $keamananipk = 0;
        $kebersihan  = 0;
        $airkotor    = 0;
        $lain        = 0;
        $jumlah      = 0;
        $diskon      = 0;

        $rin          = array();

        $i = 0;
        $j = 0;

        foreach($dataset as $d){
            $rekap[$i]['blok'] = $d->blok;
            if($shift == 2){
                $rekap[$i]['rek']  = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')]])->whereIn('shift',[1, 0])->count();
                $setor = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')]])->whereIn('shift',[1, 0])
                ->select(
                    DB::raw('SUM(byr_listrik)      as listrik'),
                    DB::raw('SUM(byr_denlistrik)   as denlistrik'),
                    DB::raw('SUM(byr_airbersih)    as airbersih'),
                    DB::raw('SUM(byr_denairbersih) as denairbersih'),
                    DB::raw('SUM(byr_keamananipk)  as keamananipk'),
                    DB::raw('SUM(byr_kebersihan)   as kebersihan'),
                    DB::raw('SUM(byr_airkotor)     as airkotor'),
                    DB::raw('SUM(byr_lain)         as lain'),
                    DB::raw('SUM(realisasi)        as jumlah'),
                    DB::raw('SUM(diskon)           as diskon'))
                ->get();
            }
            else{
                $rekap[$i]['rek']  = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')],['shift',$shift]])->count();
                $setor = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')],['shift',$shift]])
                ->select(
                    DB::raw('SUM(byr_listrik)      as listrik'),
                    DB::raw('SUM(byr_denlistrik)   as denlistrik'),
                    DB::raw('SUM(byr_airbersih)    as airbersih'),
                    DB::raw('SUM(byr_denairbersih) as denairbersih'),
                    DB::raw('SUM(byr_keamananipk)  as keamananipk'),
                    DB::raw('SUM(byr_kebersihan)   as kebersihan'),
                    DB::raw('SUM(byr_airkotor)     as airkotor'),
                    DB::raw('SUM(byr_lain)         as lain'),
                    DB::raw('SUM(realisasi)        as jumlah'),
                    DB::raw('SUM(diskon)           as diskon'))
                ->get();
            }
            $rekap[$i]['listrik']     = $setor[0]->listrik - $setor[0]->denlistrik;
            $rekap[$i]['denlistrik']  = $setor[0]->denlistrik;
            $rekap[$i]['airbersih']   = $setor[0]->airbersih - $setor[0]->denairbersih;
            $rekap[$i]['denairbersih']= $setor[0]->denairbersih;
            $rekap[$i]['keamananipk'] = $setor[0]->keamananipk;
            $rekap[$i]['kebersihan']  = $setor[0]->kebersihan;
            $rekap[$i]['airkotor']    = $setor[0]->airkotor;
            $rekap[$i]['lain']        = $setor[0]->lain;
            $rekap[$i]['diskon']      = $setor[0]->diskon;
            $rekap[$i]['jumlah']      = $setor[0]->jumlah;
            $rek         = $rek         + $rekap[$i]['rek'];
            $listrik     = $listrik     + $rekap[$i]['listrik'];
            $denlistrik  = $denlistrik  + $rekap[$i]['denlistrik'];
            $airbersih   = $airbersih   + $rekap[$i]['airbersih'];
            $denairbersih= $denairbersih+ $rekap[$i]['denairbersih'];
            $keamananipk = $keamananipk + $rekap[$i]['keamananipk'];
            $kebersihan  = $kebersihan  + $rekap[$i]['kebersihan'];
            $airkotor    = $airkotor    + $rekap[$i]['airkotor'];
            $lain        = $lain        + $rekap[$i]['lain'];
            $diskon      = $diskon      + $rekap[$i]['diskon'];
            $jumlah      = $jumlah      + $rekap[$i]['jumlah'];

            if($shift == 2){
                $rincian = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')]])->whereIn('shift',[1,0])->orderBy('kd_kontrol','asc')->get();
            }
            else{
                $rincian = Pembayaran::where([['tgl_bayar',$tanggal],['blok',$d->blok],['id_kasir',Session::get('userId')],['shift',$shift]])->orderBy('kd_kontrol','asc')->get();
            }
            foreach($rincian as $r){
                $rin[$j]['rek']  = date("m/Y", strtotime($r->tgl_tagihan));
                $rin[$j]['kode']  = $r->kd_kontrol;
                $rin[$j]['pengguna']  = $r->pengguna;
                $rin[$j]['listrik']  = $r->byr_listrik - $r->byr_denlistrik;
                $rin[$j]['denlistrik']  = $r->byr_denlistrik;
                $rin[$j]['airbersih']  = $r->byr_airbersih - $r->byr_denairbersih;
                $rin[$j]['denairbersih']  = $r->byr_denairbersih;
                $rin[$j]['keamananipk']  = $r->byr_keamananipk;
                $rin[$j]['kebersihan']  = $r->byr_kebersihan;
                $rin[$j]['airkotor']  = $r->byr_airkotor;
                $rin[$j]['lain']  = $r->byr_lain;
                $rin[$j]['jumlah']  = $r->realisasi;
                $rin[$j]['diskon']  = $r->diskon;

                $j++;
            }

            $i++;
        }
        $t_rekap['rek']          = $rek;
        $t_rekap['listrik']      = $listrik;
        $t_rekap['denlistrik']   = $denlistrik;
        $t_rekap['airbersih']    = $airbersih;
        $t_rekap['denairbersih'] = $denairbersih;
        $t_rekap['keamananipk']  = $keamananipk;
        $t_rekap['kebersihan']   = $kebersihan;
        $t_rekap['airkotor']     = $airkotor;
        $t_rekap['lain']         = $lain;
        $t_rekap['diskon']       = $diskon;
        $t_rekap['jumlah']       = $jumlah;

        if($shift == 2){
            $shift = '1 & 2';
        }
        else if ($shift == 0){
            $shift = '2';
        }
        else{
            $shift = '1';
        }
        return view('kasir.bulanan.penerimaan',[
            'tanggal'   => IndoDate::tanggal($tanggal,' '), 
            'cetak'     => $cetak,
            'rekap'     => $rekap,
            't_rekap'   => $t_rekap,
            'rincian'   => $rin,
            'shift'     => $shift,
        ]);
    }

    public function restoreStore(Request $request, $ref){
        if(request()->ajax()){
            try{
                $pembayaran = Pembayaran::where('ref',$ref)->get();
                foreach($pembayaran as $p){
                    $tagihan = Tagihan::find($p->id_tagihan);
                    if($tagihan != NULL){
                        if($p->byr_listrik == $tagihan->ttl_listrik && $p->byr_listrik !== NULL){
                            $tagihan->rea_listrik = 0;
                            $tagihan->sel_listrik = $tagihan->ttl_listrik;
                            $tagihan->den_listrik = $p->byr_denlistrik;
                            $tagihan->stt_lunas   = 0;
                        }

                        if($p->byr_airbersih == $tagihan->ttl_airbersih && $p->byr_airbersih !== NULL){
                            $tagihan->rea_airbersih = 0;
                            $tagihan->sel_airbersih = $tagihan->ttl_airbersih;
                            $tagihan->den_airbersih = $p->byr_denairbersih;
                            $tagihan->stt_lunas   = 0;
                        }

                        if($p->byr_keamananipk == $tagihan->ttl_keamananipk && $p->byr_keamananipk !== NULL){
                            $tagihan->rea_keamananipk = 0;
                            $tagihan->sel_keamananipk = $tagihan->ttl_keamananipk;
                            $tagihan->stt_lunas   = 0;
                        }

                        if($p->byr_kebersihan == $tagihan->ttl_kebersihan && $p->byr_kebersihan !== NULL){
                            $tagihan->rea_kebersihan = 0;
                            $tagihan->sel_kebersihan = $tagihan->ttl_kebersihan;
                            $tagihan->stt_lunas   = 0;
                        }

                        if($p->byr_airkotor == $tagihan->ttl_airkotor && $p->byr_airkotor !== NULL){
                            $tagihan->rea_airkotor = 0;
                            $tagihan->sel_airkotor = $tagihan->ttl_airkotor;
                            $tagihan->stt_lunas   = 0;
                        }

                        if($p->byr_lain == $tagihan->ttl_lain && $p->byr_lain !== NULL){
                            $tagihan->rea_lain = 0;
                            $tagihan->sel_lain = $tagihan->ttl_lain;
                            $tagihan->stt_lunas   = 0;
                        }
                        
                        $tagihan->stt_denda = $p->stt_denda;

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

                        $p->delete();
                    }
                    else{
                        return response()->json(['errors' => 'Restorasi Gagal']);
                    }
                }
    
                $struk = StrukPembayaran::where('ref',$ref)->first();
                if($struk != NULL){
                    $struk->delete();
                }
    
                return response()->json(['success' => 'Restorasi Sukses']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Restorasi Gagal']);
            }
        }
    }

    public function getutama(Request $request){
        $tanggal = $request->tgl_utama;
        $tgl = $tanggal;
        $tanggal = IndoDate::tanggal($tanggal, ' ');
        $cetak = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

        $data  = User::where('role','kasir')->get();
        $i = 0;
        $dataset = array();
        foreach($data as $d){
            $dataset[$i]['nama'] = $d->nama;

            $harian = Harian::where([['tgl_bayar',$tgl],['id_kasir',$d->id]])->get();
            $har_total  = 0;
            foreach($harian as $t){
                $har_total = $har_total + $t->total;
            }
            $hari = $har_total;

            $bulanan = Pembayaran::where([['tgl_bayar',$tgl],['id_kasir',$d->id]])->get();
            $bul_total  = 0;
            foreach($bulanan as $b){
                $bul_total = $bul_total + $b->realisasi;
            }
            $bulan = $bul_total;

            $dataset[$i]['bulanan'] = $bulan;
            $dataset[$i]['harian'] = $hari;
            $dataset[$i]['jumlah'] = $bulan + $hari;

            $i++;
        }

        $rincianbulan = Pembayaran::where('tgl_bayar',$tgl)->orderBy('kd_kontrol','asc')->get();

        return view('kasir.utama',[
            'dataset' => $dataset,
            'rincianbulan' => $rincianbulan,
            'cetak'   => $cetak,
            'tanggal' => $tanggal,
        ]);
    }

    public function getUtamaBulan(Request $request){
        $periode = $request->tahunpendapatan."-".$request->bulanpendapatan;
        $bln = IndoDate::bulan($periode, ' ');
        $cetak = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

        $data  = User::where('role','kasir')->get();
        $i = 0;
        $dataset = array();
        $rincianbulan = array();
        foreach($data as $d){
            $dataset[$i]['nama'] = $d->nama;

            $harian = Harian::where([['bln_bayar',$periode],['id_kasir',$d->id]])->get();
            $har_total  = 0;
            foreach($harian as $t){
                $har_total = $har_total + $t->total;
            }
            $hari = $har_total;

            $bulanan = Pembayaran::where([['bln_bayar',$periode],['id_kasir',$d->id]])->get();
            $bul_total  = 0;
            foreach($bulanan as $b){
                $bul_total = $bul_total + $b->realisasi;
            }
            $bulan = $bul_total;

            $dataset[$i]['bulanan'] = $bulan;
            $dataset[$i]['harian'] = $hari;
            $dataset[$i]['jumlah'] = $bulan + $hari;

            $rinbul = Pembayaran::where([['bln_bayar',$periode],['id_kasir',$d->id]])->select('tgl_bayar')->groupBy('tgl_bayar')->get();
            $j = 0;
            foreach($rinbul as $r){
                $setor = Pembayaran::where([['tgl_bayar',$r->tgl_bayar],['id_kasir',$d->id]])->get();
                $listrik = 0;
                $denlistrik = 0;
                $airbersih = 0;
                $denairbersih = 0;
                $keamananipk = 0;
                $kebersihan = 0;
                $airkotor = 0;
                $lain = 0;
                $jumlah = 0;
                foreach($setor as $s){
                    $listrik = $listrik + $s->byr_listrik - $s->byr_denlistrik;
                    $denlistrik = $denlistrik + $s->byr_denlistrik;
                    $airbersih = $airbersih + $s->byr_airbersih - $s->byr_denairbersih;
                    $denairbersih = $denairbersih + $s->byr_denairbersih;
                    $keamananipk = $keamananipk + $s->byr_keamananipk;
                    $kebersihan = $kebersihan + $s->byr_kebersihan;
                    $airkotor = $airkotor + $s->byr_airkotor;
                    $lain = $lain + $s->byr_lain;
                    $jumlah = $listrik + $denlistrik + $airbersih + $denairbersih + $keamananipk + $kebersihan + $airkotor + $lain;
                }
                $rincianbulan[$i][$j]['nama']  = $d->nama;
                $rincianbulan[$i][$j]['setor'] = $r->tgl_bayar;
                $rincianbulan[$i][$j]['listrik'] = $listrik;
                $rincianbulan[$i][$j]['denlistrik'] = $denlistrik;
                $rincianbulan[$i][$j]['airbersih'] = $airbersih;
                $rincianbulan[$i][$j]['denairbersih'] = $denairbersih;
                $rincianbulan[$i][$j]['keamananipk'] = $keamananipk;
                $rincianbulan[$i][$j]['kebersihan'] = $kebersihan;
                $rincianbulan[$i][$j]['airkotor'] = $airkotor;
                $rincianbulan[$i][$j]['lain'] = $lain;
                $rincianbulan[$i][$j]['jumlah'] = $jumlah;
                $j++;
            }

            $i++;
        }

        return view('kasir.utama-bulan',[
            'dataset' => $dataset,
            'rincianbulan' => $rincianbulan,
            'cetak'   => $cetak,
            'bulan'   => $bln,
        ]);
    }

    public function getsisa(Request $request){
        $bulan   = IndoDate::bulan(date('Y-m',strtotime(Carbon::now())),' ');
        
        if($request->sisatagihan == 'all'){
            $dataset = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]])->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
        }
        else{
            $dataset = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]])->whereIn('blok',$request->sebagian)->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
        }
        
        $rekap       = array();
        $rek         = 0;
        $listrik     = 0;
        $denlistrik  = 0;
        $airbersih   = 0;
        $denairbersih= 0;
        $keamananipk = 0;
        $kebersihan  = 0;
        $airkotor    = 0;
        $lain        = 0;
        $jumlah      = 0;
        $diskon      = 0;

        $rin          = array();

        $i = 0;
        $j = 0;

        foreach($dataset as $d){
            $rekap[$i]['blok'] = $d->blok;
            $rekap[$i]['rek']  = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$d->blok]])->count();
            $setor = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$d->blok]])
            ->select(
                DB::raw('SUM(sel_listrik)      as listrik'),
                DB::raw('SUM(den_listrik)      as denlistrik'),
                DB::raw('SUM(sel_airbersih)    as airbersih'),
                DB::raw('SUM(den_airbersih)    as denairbersih'),
                DB::raw('SUM(sel_keamananipk)  as keamananipk'),
                DB::raw('SUM(sel_kebersihan)   as kebersihan'),
                DB::raw('SUM(sel_airkotor)     as airkotor'),
                DB::raw('SUM(sel_lain)         as lain'),
                DB::raw('SUM(sel_tagihan)      as jumlah'),
                DB::raw('SUM(dis_tagihan)      as diskon'))
            ->get();
            
            $rekap[$i]['listrik']     = $setor[0]->listrik - $setor[0]->denlistrik;
            $rekap[$i]['denlistrik']  = $setor[0]->denlistrik;
            $rekap[$i]['airbersih']   = $setor[0]->airbersih - $setor[0]->denairbersih;
            $rekap[$i]['denairbersih']= $setor[0]->denairbersih;
            $rekap[$i]['keamananipk'] = $setor[0]->keamananipk;
            $rekap[$i]['kebersihan']  = $setor[0]->kebersihan;
            $rekap[$i]['airkotor']    = $setor[0]->airkotor;
            $rekap[$i]['lain']        = $setor[0]->lain;
            $rekap[$i]['diskon']      = $setor[0]->diskon;
            $rekap[$i]['jumlah']      = $setor[0]->jumlah;
            $rek         = $rek         + $rekap[$i]['rek'];
            $listrik     = $listrik     + $rekap[$i]['listrik'];
            $denlistrik  = $denlistrik  + $rekap[$i]['denlistrik'];
            $airbersih   = $airbersih   + $rekap[$i]['airbersih'];
            $denairbersih= $denairbersih+ $rekap[$i]['denairbersih'];
            $keamananipk = $keamananipk + $rekap[$i]['keamananipk'];
            $kebersihan  = $kebersihan  + $rekap[$i]['kebersihan'];
            $airkotor    = $airkotor    + $rekap[$i]['airkotor'];
            $lain        = $lain        + $rekap[$i]['lain'];
            $diskon      = $diskon      + $rekap[$i]['diskon'];
            $jumlah      = $jumlah      + $rekap[$i]['jumlah'];

            $rincian = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$d->blok]])->orderBy('kd_kontrol','asc')->get();
            foreach($rincian as $r){
                $rin[$j]['blok']  = $r->blok;
                $rin[$j]['kode']  = $r->kd_kontrol;
                $rin[$j]['pengguna']  = $r->nama;
                $rin[$j]['listrik']  = $r->sel_listrik - $r->den_listrik;
                $rin[$j]['denlistrik']  = $r->den_listrik;
                $rin[$j]['airbersih']  = $r->sel_airbersih - $r->den_airbersih;
                $rin[$j]['denairbersih']  = $r->den_airbersih;
                $rin[$j]['keamananipk']  = $r->sel_keamananipk;
                $rin[$j]['kebersihan']  = $r->sel_kebersihan;
                $rin[$j]['airkotor']  = $r->sel_airkotor;
                $rin[$j]['lain']  = $r->sel_lain;
                $rin[$j]['jumlah']  = $r->sel_tagihan;
                $rin[$j]['diskon']  = $r->dis_tagihan;

                $tempat = TempatUsaha::where('kd_kontrol',$r->kd_kontrol)->first();
                if($tempat != NULL){
                    $rin[$j]['lokasi'] = $tempat->lok_tempat;
                }
                else{
                    $rin[$j]['lokasi'] = '';
                }

                $j++;
            }

            $i++;
        }
        $t_rekap['rek']          = $rek;
        $t_rekap['listrik']      = $listrik;
        $t_rekap['denlistrik']   = $denlistrik;
        $t_rekap['airbersih']    = $airbersih;
        $t_rekap['denairbersih'] = $denairbersih;
        $t_rekap['keamananipk']  = $keamananipk;
        $t_rekap['kebersihan']   = $kebersihan;
        $t_rekap['airkotor']     = $airkotor;
        $t_rekap['lain']         = $lain;
        $t_rekap['diskon']       = $diskon;
        $t_rekap['jumlah']       = $jumlah;

        return view('kasir.bulanan.sisa',[
            'dataset' => $dataset,
            'bulan' => $bulan,
            'rekap'     => $rekap,
            't_rekap'   => $t_rekap,
            'rincian'   => $rin
        ]);
    }

    public function getselesai(Request $request){
        $cetak   = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));
        $periode = $request->tahunselesai."-".$request->bulanselesai;
        $data = Pembayaran::where([['bln_bayar',$periode],['id_kasir',Session::get('userId')]])->get();
        $listrik = 0;
        $denlistrik = 0;
        $airbersih = 0;
        $denairbersih = 0;
        $keamananipk = 0;
        $kebersihan = 0;
        $airkotor = 0;
        $lain = 0;
        foreach($data as $d){
            $listrik = $listrik + $d->byr_listrik;
            $denlistrik = $denlistrik + $d->byr_denlistrik;
            $airbersih = $airbersih + $d->byr_airbersih;
            $denairbersih = $denairbersih + $d->byr_denairbersih;
            $keamananipk = $keamananipk + $d->byr_keamananipk;
            $kebersihan = $kebersihan + $d->byr_kebersihan;
            $airkotor = $airkotor + $d->byr_airkotor;
            $lain = $lain + $d->byr_lain;
        }
        $dataset = array();
        $dataset[0]['items'] = 'Listrik';
        $dataset[0]['total'] = $listrik - $denlistrik;
        $dataset[0]['denda'] = $denlistrik;
        $dataset[1]['items'] = 'Air Bersih';
        $dataset[1]['total'] = $airbersih - $denairbersih;
        $dataset[1]['denda'] = $denairbersih;
        $dataset[2]['items'] = 'Keamanan IPK';
        $dataset[2]['total'] = $keamananipk;
        $dataset[2]['denda'] = NULL;
        $dataset[3]['items'] = 'Kebersihan';
        $dataset[3]['total'] = $kebersihan;
        $dataset[3]['denda'] = NULL;
        $dataset[4]['items'] = 'Air Kotor';
        $dataset[4]['total'] = $airkotor;
        $dataset[4]['denda'] = NULL;
        $dataset[5]['items'] = 'Lain - Lain';
        $dataset[5]['total'] = $lain;
        $dataset[5]['denda'] = NULL;
        $bulan = IndoDate::bulan($periode," ");

        $rincian = Pembayaran::where([['bln_bayar',$periode],['id_kasir',Session::get('userId')]])->orderBy('tgl_bayar','asc')->orderBy('kd_kontrol','asc')->get();

        return view('kasir.bulanan.selesai',[
            'dataset' => $dataset,
            'rincian' => $rincian,
            'bulan'   => $bulan,
            'cetak'   => $cetak
        ]);
    }

    public function cetakStruk(Request $request, $struk, $id){
        if($struk == 'tagihan'){
            $struk = StrukPembayaran::find($id);

            $listrik         = number_format($struk->taglistrik);
            $tunglistrik     = number_format($struk->tagtunglistrik);
            $denlistrik      = number_format($struk->tagdenlistrik);
            $dayalistrik     = number_format($struk->tagdylistrik);
            $awallistrik     = number_format($struk->tagawlistrik);
            $akhirlistrik    = number_format($struk->tagaklistrik);
            $pakailistrik    = number_format($struk->tagpklistrik);
            
            $airbersih       = number_format($struk->tagairbersih);
            $tungairbersih   = number_format($struk->tagtungairbersih);
            $denairbersih    = number_format($struk->tagdenairbersih);
            $awalairbersih   = number_format($struk->tagawairbersih);
            $akhirairbersih  = number_format($struk->tagakairbersih);
            $pakaiairbersih  = number_format($struk->tagpkairbersih);

            $keamananipk     = number_format($struk->tagkeamananipk);
            $tungkeamananipk = number_format($struk->tagtungkeamananipk);
            
            $kebersihan      = number_format($struk->tagkebersihan);
            $tungkebersihan  = number_format($struk->tagtungkebersihan);
            
            $airkotor        = number_format($struk->tagairkotor);
            $tungairkotor    = number_format($struk->tagtungairkotor);
            
            $lain            = number_format($struk->taglain);
            $tunglain        = number_format($struk->tagtunglain);

            $total           = number_format($struk->totalTagihan);

            if($struk->cetakan > 0)
                $cetakan     = number_format($struk->cetakan);
            else
                $cetakan     = 0;

            $dirfile = storage_path('app/public/logo_struk.png');
            $logo = EscposImage::load($dirfile,false);

            $bulan = IndoDate::bulanS($struk->bln_bayar, " ");

            $profile   = CapabilityProfile::load("POS-5890");
            $connector = new RawbtPrintConnector();
            $printer   = new Printer($connector,$profile);
            $i = 1;
            try{
                if(Session::get('printer') == 'panda'){
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> setEmphasis(true);
                    $printer -> text("Nama    : $struk->pedagang\n");
                    $printer -> text("Kontrol : $struk->kd_kontrol\n");
                    $printer -> text("Los     : $struk->los\n");
                    if($struk->lokasi != ''){
                        $printer -> text("Lokasi  : $struk->lokasi\n");
                    }
                    $printer -> text("No.Ref  : $struk->ref\n");
                    $printer -> setEmphasis(false);
                    $printer -> feed();
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("$bulan\n");
                    $printer -> feed();
                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> text(new Struk80mm("Items","Rp.",true, true));
                    $printer -> selectPrintMode();
                    $printer -> setFont(Printer::FONT_B);
                    $printer -> text("----------------------------------------\n");
                    $feed = 0;
                    if($struk->taglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Listrik",$listrik,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk80mm("Daya" ,$dayalistrik,false));
                        $printer -> text(new Struk80mm("Awal" ,$awallistrik,false));
                        $printer -> text(new Struk80mm("Akhir",$akhirlistrik,false));
                        $printer -> text(new Struk80mm("Pakai",$pakailistrik,false));
                        $i++;
                        $feed = 1;
                    }
                    if($struk->tagtunglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Listrik",$tunglistrik,true));
                        $i++;
                        $feed = 1;
                    }
                    if($struk->tagdenlistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Den.Listrik",$denlistrik,true));
                        $i++;
                        $feed = 1;
                    }

                    if($feed == 1)
                        $printer -> feed();
                    
                    if($struk->tagairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Air Bersih",$airbersih,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk80mm("Awal" ,$awalairbersih,false));
                        $printer -> text(new Struk80mm("Akhir",$akhirairbersih,false));
                        $printer -> text(new Struk80mm("Pakai",$pakaiairbersih,false));
                        $i++;
                        $feed = 2;
                    }
                    if($struk->tagtungairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Air Bersih",$tungairbersih,true));
                        $i++;
                        $feed = 2;
                    }
                    if($struk->tagdenairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Den.Air Bersih",$denairbersih,true));
                        $i++;
                        $feed = 2;
                    }
                    
                    if($feed == 2)
                        $printer -> feed();
                    
                    if($struk->tagkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Keamanan IPK",$keamananipk,true));
                        $i++;
                        $feed = 3;
                    }
                    if($struk->tagtungkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Keamanan IPK",$tungkeamananipk,true));
                        $i++;
                        $feed = 3;
                    }
                    
                    if($feed == 3)
                        $printer -> feed();
                    
                    if($struk->tagkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Kebersihan",$kebersihan,true));
                        $i++;
                        $feed = 4;
                    }
                    if($struk->tagtungkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Kebersihan",$tungkebersihan,true));
                        $i++;
                        $feed = 4;
                    }
                    
                    if($feed == 4)
                        $printer -> feed();
                    
                    if($struk->tagairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Air Kotor",$airkotor,true));
                        $i++;
                        $feed = 5;
                    }
                    if($struk->tagtungairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Air Kotor",$tungairkotor,true));
                        $i++;
                        $feed = 5;
                    }
                    
                    if($feed == 5)
                        $printer -> feed();
                    
                    if($struk->taglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Lain Lain",$lain,true));
                        $i++;
                        $feed = 6;
                    }
                    if($struk->tagtunglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk80mm("$i. Tgk.Lain Lain",$tunglain,true));
                        $i++;
                        $feed = 6;
                    }
                    
                    if($feed == 6)
                        $printer -> feed();
                    
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> text(new Struk80mm("Total",$total,true,true));
                    $printer -> selectPrintMode();
                    $printer -> setFont(Printer::FONT_B);
                    $printer -> text("----------------------------------------\n");
                    if($cetakan != 0)
                        $printer -> text("Salinan ke-$cetakan\n");
                    $printer -> text("Nomor : $struk->nomor\n");
                    $printer -> text("Dibayar pada $struk->bayar\n");
                    $printer -> text("Harap simpan tanda terima ini\nsebagai bukti pembayaran yang sah.\nTerimakasih.\nPembayaran sudah termasuk PPN\n");
                    $printer -> text("Ksr : $struk->kasir\n");
                    $printer -> feed();
                }
                else if(Session::get('printer') == 'androidpos'){

                }
                else{
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);
                    $printer -> setJustification(Printer::JUSTIFY_LEFT);
                    $printer -> setEmphasis(true);
                    $printer -> text("Nama    : $struk->pedagang\n");
                    $printer -> text("Kontrol : $struk->kd_kontrol\n");
                    $printer -> text("Los     : $struk->los\n");
                    if($struk->lokasi != ''){
                        $printer -> text("Lokasi  : $struk->lokasi\n");
                    }
                    $printer -> text("No.Ref  : $struk->ref\n");
                    $printer -> setEmphasis(false);
                    $printer -> feed();
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> text("$bulan\n");
                    $printer -> feed();
                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> text(new Struk70mm("Items","Rp.",true, true));
                    $printer -> selectPrintMode();
                    $printer -> setFont(Printer::FONT_B);
                    $printer -> text("----------------------------------------\n");
                    $feed = 0;
                    if($struk->taglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Listrik",$listrik,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Daya" ,$dayalistrik,false));
                        $printer -> text(new Struk70mm("Awal" ,$awallistrik,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirlistrik,false));
                        $printer -> text(new Struk70mm("Pakai",$pakailistrik,false));
                        $i++;
                        $feed = 1;
                    }
                    if($struk->tagtunglistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Listrik",$tunglistrik,true));
                        $i++;
                        $feed = 1;
                    }
                    if($struk->tagdenlistrik != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Listrik",$denlistrik,true));
                        $i++;
                        $feed = 1;
                    }
                    
                    if($feed == 1)
                        $printer -> feed();
                    
                    if($struk->tagairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Bersih",$airbersih,true));
                        $printer -> setJustification(Printer::JUSTIFY_LEFT);
                        $printer -> text(new Struk70mm("Awal" ,$awalairbersih,false));
                        $printer -> text(new Struk70mm("Akhir",$akhirairbersih,false));
                        $printer -> text(new Struk70mm("Pakai",$pakaiairbersih,false));
                        $i++;
                        $feed = 2;
                    }
                    if($struk->tagtungairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Bersih",$tungairbersih,true));
                        $i++;
                        $feed = 2;
                    }
                    if($struk->tagdenairbersih != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Den.Air Bersih",$denairbersih,true));
                        $i++;
                        $feed = 2;
                    }
                    
                    if($feed == 2)
                        $printer -> feed();
                    
                    if($struk->tagkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Keamanan IPK",$keamananipk,true));
                        $i++;
                        $feed = 3;
                    }
                    if($struk->tagtungkeamananipk != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Keamanan IPK",$tungkeamananipk,true));
                        $i++;
                        $feed = 3;
                    }
                    
                    if($feed == 3)
                        $printer -> feed();
                    
                    if($struk->tagkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Kebersihan",$kebersihan,true));
                        $i++;
                        $feed = 4;
                    }
                    if($struk->tagtungkebersihan != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Kebersihan",$tungkebersihan,true));
                        $i++;
                        $feed = 4;
                    }
                    
                    if($feed == 4)
                        $printer -> feed();
                    
                    if($struk->tagairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Air Kotor",$airkotor,true));
                        $i++;
                        $feed = 5;
                    }
                    if($struk->tagtungairkotor != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Air Kotor",$tungairkotor,true));
                        $i++;
                        $feed = 5;
                    }
                    
                    if($feed == 5)
                        $printer -> feed();
                    
                    if($struk->taglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Lain Lain",$lain,true));
                        $i++;
                        $feed = 6;
                    }
                    if($struk->tagtunglain != 0){
                        $printer -> setJustification(Printer::JUSTIFY_CENTER);
                        $printer -> text(new Struk70mm("$i. Tgk.Lain Lain",$tunglain,true));
                        $i++;
                        $feed = 6;
                    }
                    
                    if($feed == 6)
                        $printer -> feed();
                    
                    $printer -> setJustification(Printer::JUSTIFY_CENTER);
                    $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer -> text(new Struk70mm("Total",$total,true,true));
                    $printer -> selectPrintMode();
                    $printer -> setFont(Printer::FONT_B);
                    $printer -> text("----------------------------------------\n");
                    if($cetakan != 0)
                        $printer -> text("Salinan ke-$cetakan\n");
                    $printer -> text("Nomor : $struk->nomor\n");
                    $printer -> text("Dibayar pada $struk->bayar\n");
                    $printer -> text("Harap simpan tanda terima ini\nsebagai bukti pembayaran yang sah.\nTerimakasih.\nPembayaran sudah termasuk PPN\n");
                    $printer -> text("Ksr : $struk->kasir\n");
                    $printer -> feed();
                    $printer -> cut();
                }
                $cetakan++;
                $struk->cetakan = $cetakan;
                $struk->save();
            }catch(\Exception $e){
                return response()->json(['status' => 'Gagal Print Struk']);
            }finally{
                $printer->close();
            }
        }
    }
}
