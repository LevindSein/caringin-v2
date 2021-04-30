<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;
use Validator;
use Exception;

use App\Models\TempatUsaha;
use App\Models\Pedagang;

use App\Models\AlatListrik;
use App\Models\AlatAir;

use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

use App\Models\PasangAlat;
use App\Models\Sinkronisasi;
use App\Models\Tagihan;
use App\Models\Bongkaran;

use App\Models\IndoDate;

use App\Models\Dokumen;

use App\Models\LevindCrypt;

use Carbon\Carbon;

class TempatController extends Controller
{
    public function __construct()
    {
        $this->middleware('tempatusaha');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(request()->ajax())
        {
            $data = DB::table('tempat_usaha')->leftJoin('user','tempat_usaha.id_pengguna','=','user.id')
            ->select('tempat_usaha.id as id',
                'tempat_usaha.kd_kontrol as kd_kontrol',
                'tempat_usaha.no_alamat as no_alamat',
                'tempat_usaha.id_pengguna as id_pengguna',
                'tempat_usaha.lok_tempat as lok_tempat',
                'tempat_usaha.jml_alamat as jml_alamat',
                'tempat_usaha.bentuk_usaha as bentuk_usaha',
                'tempat_usaha.stt_tempat as stt_tempat',
                'user.nama as nama');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" data-toggle="tooltip" data-original-title="Print QR" name="qr" id="'.LevindCrypt::encryptString($data->id).'" class="qr"><i class="fas fa-qrcode" style="color:#fd7e14;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Edit Tempat" name="edit" id="'.LevindCrypt::encryptString($data->id).'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Hapus Tempat" name="delete" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#e74a3b;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->editColumn('jml_alamat', function($data){
                    $button = '<span data-html="true" data-toggle="tooltip" data-original-title="No. '.$data->no_alamat.'">'.$data->jml_alamat.'</span>';
                    return $button;
                })
                ->editColumn('kd_kontrol', function ($data) {
                    if($data->stt_tempat == 1)
                        return '<span style="color:green;">'.$data->kd_kontrol.'</span>';
                    else if($data->stt_tempat == 2)
                        return '<span style="color:red;">'.$data->kd_kontrol.'</span>';
                    else
                        return '<span>'.$data->kd_kontrol.'</span>';
                })
                ->editColumn('id_pengguna', function($data){
                    if($data->nama == null) return '<span style="color:green;">idle</span>';
                    return $data->nama;
                })
                ->rawColumns([
                    'action',
                    'show',
                    'jml_alamat',
                    'id_pengguna',
                    'kd_kontrol'])
                ->make(true);
        }
        return view('tempatusaha.data',[
            'airAvailable'=>TempatUsaha::airAvailable(),
            'listrikAvailable'=>TempatUsaha::listrikAvailable(),
            'trfKeamananIpk'=>TempatUsaha::trfKeamananIpk(),
            'trfKebersihan'=>TempatUsaha::trfKebersihan(),
            'trfAirKotor'=>TempatUsaha::trfAirKotor(),
            'trfLain'=>TempatUsaha::trfLain()
        ]);
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
        if(request()->ajax()){
            try{
                $rules = array(
                    'blok'     => 'required',
                    'los'      => ['required', 'regex:/^[a-zA-Z0-9\,\s]+$/u','min:1', 'max:50'],
                    'lokasi'   => ['max:50'],
                    'usaha'    => ['max:30'],
                );
    
                $error = Validator::make($request->all(), $rules);
    
                if($error->fails())
                {
                    return response()->json(['errors' => 'Gagal Mengambil Data']);
                }

                //deklarasi model
                $tempat = new TempatUsaha;

                //blok
                $blok = $request->blok;
                $tempat->blok = $blok;
                
                //no_alamat
                $los = strtoupper($request->los);
                $tempat->no_alamat = $los;
                
                //jml_alamat
                $los = explode(",",$los);
                $tempat->jml_alamat = count($los);
                
                //kd_kontrol
                $kode = TempatUsaha::kode($blok,$los);
                $tempat->kd_kontrol = $kode;
                
                //bentuk_usaha
                if($request->usaha != null)
                    $tempat->bentuk_usaha = ucwords($request->usaha);
                else
                    $tempat->bentuk_usaha = NULL;
                
                //lok_tempat
                $lokasi = $request->lokasi;
                if($lokasi != NULL){
                    $tempat->lok_tempat = $lokasi;
                }

                //id_pemilik
                $id_pemillik = $request->pemilik;
                $tempat->id_pemilik = $id_pemillik;

                // //id_pengguna
                $id_pengguna = $request->pengguna;
                $tempat->id_pengguna = $id_pengguna;

                //Fasilitas
                if(empty($request->air) == FALSE){
                    $tempat->trf_airbersih = 1;
                    $id_meteran_air = $request->meterAir;
                    $tempat->id_meteran_air = $id_meteran_air;

                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();

                    $diskon = array();
                    if($request->radioAirBersih == "semua_airbersih"){
                        $tempat->dis_airbersih = NULL;
                    }
                    else if($request->radioAirBersih == 'dis_airbersih'){
                        if($request->persenDiskonAir != NULL){
                            $diskon['type'] = 'diskon';
                            $diskon['value'] = $request->persenDiskonAir;
                            $tempat->dis_airbersih = json_encode($diskon);
                        }
                        else{
                            $tempat->dis_airbersih = NULL;
                        }
                    }
                    else{
                        $pilihanDiskon = array('byr','beban','pemeliharaan','arkot','charge');
                        if($request->hanya != NULL){
                            $diskon['type'] = 'hanya';
                            $hanya = array();
                            $j = 0;
                            for($i=0; $i<count($pilihanDiskon); $i++){
                                if(in_array($pilihanDiskon[$i],$request->hanya)){
                                    if($pilihanDiskon[$i] == 'charge'){
                                        if($request->persenChargeAir != NULL){
                                            $persen = $request->persenChargeAir;
                                            $dari = $request->chargeAir;
                                            $value = $persen.','.$dari;
                                            $hanya[$j] = [$pilihanDiskon[$i] => $value];
                                        }
                                    }
                                    else{
                                        $hanya[$j] = $pilihanDiskon[$i];
                                    }
                                    $j++;
                                }
                            }
                            $diskon['value'] = $hanya;
                            $tempat->dis_airbersih = json_encode($diskon);
                        }
                        else{
                            $tempat->dis_airbersih = NULL;
                        }
                    }
                }

                if(empty($request->listrik) == FALSE){
                    $tempat->trf_listrik = 1;
                    $id_meteran_listrik = $request->meterListrik;
                    $tempat->id_meteran_listrik = $id_meteran_listrik;

                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();

                    if(empty($request->dis_listrik) == FALSE){
                        if($request->persenDiskonListrik == NULL){
                            $tempat->dis_listrik = 0;
                        }
                        else{
                            $tempat->dis_listrik = $request->persenDiskonListrik;
                        }
                    }
                    else{
                        $tempat->dis_listrik = NULL;
                    }
                }

                if(empty($request->keamananipk) == FALSE){
                    $tarif = TarifKeamananIpk::where('tarif',$request->trfKeamananIpk)->select('id')->first();
                    $tempat->trf_keamananipk = $tarif->id;

                    if(empty($request->dis_keamananipk) == FALSE){
                        if($request->diskonKeamananIpk == NULL){
                            $tempat->dis_keamananipk = 0;
                        }
                        else{
                            $diskon = explode(',',$request->diskonKeamananIpk);
                            $diskon = implode('',$diskon);
                            $tempat->dis_keamananipk = $diskon;
                        }
                    }
                    else{
                        $tempat->dis_keamananipk = NULL;
                    }
                }

                if(empty($request->kebersihan) == FALSE){
                    $tarif = TarifKebersihan::where('tarif',$request->trfKebersihan)->select('id')->first();
                    $tempat->trf_kebersihan = $tarif->id;

                    if(empty($request->dis_kebersihan) == FALSE){
                        if($request->diskonKebersihan == NULL){
                            $tempat->dis_kebersihan = 0;
                        }
                        else{
                            $diskon = explode(',',$request->diskonKebersihan);
                            $diskon = implode('',$diskon);
                            $tempat->dis_kebersihan = $diskon;
                        }
                    }
                    else{
                        $tempat->dis_kebersihan = NULL;
                    }
                }

                if(empty($request->airkotor) == FALSE){
                    $tempat->trf_airkotor = $request->trfAirKotor;
                }
                else{
                    $tempat->trf_airkotor = NULL;
                }

                if(empty($request->lain) == FALSE){
                    $tempat->trf_lain = $request->trfLain;
                }
                else{
                    $tempat->trf_lain = NULL;
                }

                // stt_cicil / Metode Pembayaran
                $stt_cicil = $request->cicilan;
                if($stt_cicil == "0"){
                    $tempat->stt_cicil = 0; //Kontan
                }
                else if ($stt_cicil == "1"){
                    $tempat->stt_cicil = 1; //Cicil
                }

                // stt_tempat
                $stt_tempat = $request->status;
                if($stt_tempat == "1"){
                    $tempat->stt_tempat = 1;
                }
                else if($stt_tempat == "2"){
                    $tempat->stt_tempat = 2;
                    $tempat->ket_tempat = $request->ket_tempat;
                }

                //Save Record Tempat Usaha Baru
                $tempat->save();
                return response()->json(['success' => 'Data Berhasil Ditambah.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }
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
        if(request()->ajax())
        {
            $id = LevindCrypt::decryptString($id);
            $data = TempatUsaha::find($id);

            $data['pengguna'] = NULL;
            $data['hp_pengguna'] = NULL;
            $data['email_pengguna'] = NULL;
            $data['pemilik'] = NULL;

            //fasilitas 
            $data['faslistrik'] = NULL;
            $data['fasairbersih'] = NULL;
            $data['faskeamananipk'] = NULL;
            $data['faskebersihan'] = NULL;
            $data['fasairkotor'] = NULL;
            $data['faslain'] = NULL;

            if($data != null){
                if($data->id_pengguna != null){
                    $pedagang = Pedagang::find($data->id_pengguna);
                    $data['pengguna'] = $pedagang->nama;
                    $data['hp_pengguna'] = $pedagang->hp;
                    $data['email_pengguna'] = $pedagang->email;
                }
                if($data->id_pemilik != null){
                    $pedagang = Pedagang::find($data->id_pemilik);
                    $data['pemilik'] = $pedagang->nama;
                }

                //Fasilitas
                if($data->trf_listrik != null){
                    $data['faslistrik'] = $data->trf_listrik;
                    $data['diskonlistrik'] = $data->dis_listrik;

                    $alat = AlatListrik::find($data->id_meteran_listrik);
                    $data['alatlistrik'] = $alat->kode;
                    $data['dayalistrik'] = $alat->daya;
                    $data['standlistrik'] = $alat->akhir;
                }
                if($data->trf_airbersih != null){
                    $data['fasairbersih'] = $data->trf_airbersih;
                    $data['diskonairbersih'] = $data->dis_airbersih;

                    $alat = AlatAir::find($data->id_meteran_air);
                    $data['alatairbersih'] = $alat->kode;
                    $data['standairbersih'] = $alat->akhir;
                }
                if($data->trf_keamananipk != null){
                    $data['faskeamananipk'] = $data->trf_keamananipk;
                    $data['diskonkeamananipk'] = $data->dis_keamananipk;

                    $tarif = TarifKeamananIpk::find($data->trf_keamananipk);
                    $data['perunitkeamananipk'] = $tarif->tarif;
                    $data['subtotalkeamananipk'] = $tarif->tarif * $data->jml_alamat;
                    $data['totalkeamananipk'] = ($tarif->tarif * $data->jml_alamat) - $data->dis_keamananipk;
                }
                if($data->trf_kebersihan != null){
                    $data['faskebersihan'] = $data->trf_kebersihan;
                    $data['diskonkebersihan'] = $data->dis_kebersihan;

                    $tarif = TarifKebersihan::find($data->trf_kebersihan);
                    $data['perunitkebersihan'] = $tarif->tarif;
                    $data['subtotalkebersihan'] = $tarif->tarif * $data->jml_alamat;
                    $data['totalkebersihan'] = ($tarif->tarif * $data->jml_alamat) - $data->dis_kebersihan;
                }
                if($data->trf_airkotor != null){
                    $data['fasairkotor'] = $data->trf_airkotor;

                    $tarif = TarifAirKotor::find($data->trf_airkotor);
                    $data['totalairkotor'] = $tarif->tarif;
                }
                if($data->trf_lain != null){
                    $data['faslain'] = $data->trf_lain;

                    $tarif = TarifLain::find($data->trf_lain);
                    $data['totallain'] = $tarif->tarif;
                }
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
        if(request()->ajax())
        {
            $id = LevindCrypt::decryptString($id);
            $data = TempatUsaha::find($id);

            $pemilik = Pedagang::find($data->id_pemilik);
            if($pemilik != NULL) {
                $data['id_pemilik'] = $pemilik->id;
                $data['pemilik'] = $pemilik->nama;
            }
            else {
                $data['id_pemilik'] = null;
            }

            $pengguna = Pedagang::find($data->id_pengguna);
            if($pengguna != NULL) {
                $data['id_pengguna'] = $pengguna->id;
                $data['pengguna'] = $pengguna->nama;
            }
            else {
                $data['id_pengguna'] = null;
            }

            if($data->id_meteran_air !== NULL || $data->trf_airbersih !== NULL){
                $meterAir = AlatAir::find($data->id_meteran_air);
                if($meterAir !== NULL){
                    $data['meterAir'] = $meterAir->kode." - ".$meterAir->nomor." (".$meterAir->akhir.")";
                    $data['meterAirId'] = $meterAir->id;
                }
                else {
                    $data['meterAirId'] = null;
                }

                if($data->dis_airbersih != NULL){
                    $diskon = json_decode($data->dis_airbersih);
                    if($diskon->type == 'diskon'){
                        $data['bebasAir'] = 'diskon';
                        $data['diskonAir'] = $diskon->value;
                    }
                    else{
                        $data['bebasAir'] = 'hanya';
                        $data['diskonAir'] = $diskon->value;
                    }
                }
            }

            if($data->id_meteran_listrik !== NULL || $data->trf_listrik !== NULL){
                $meterListrik = AlatListrik::find($data->id_meteran_listrik);
                if($meterListrik !== NULL){
                    $data['meterListrik'] = $meterListrik->kode." - ".$meterListrik->nomor." (".$meterListrik->akhir.' - '.$meterListrik->daya." W)";
                    $data['meterListrikId'] = $meterListrik->id;
                }
                else {
                    $data['meterListrikId'] = null;
                }
            }

            if($data->trf_keamananipk != NULL){
                $tarif = TarifKeamananIpk::find($data->trf_keamananipk);
                if($tarif != NULL){
                    $data['tarifKeamananIpk'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifKeamananIpkId'] = $tarif->tarif;
                }
            }

            if($data->trf_kebersihan != NULL){
                $tarif = TarifKebersihan::find($data->trf_kebersihan);
                if($tarif != NULL){
                    $data['tarifKebersihan'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifKebersihanId'] = $tarif->tarif;
                }
            }

            if($data->trf_airkotor != NULL){
                $tarif = TarifAirKotor::find($data->trf_airkotor);
                if($tarif != NULL){
                    $data['tarifAirKotor'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifAirKotorId'] = $tarif->id;
                }
            }

            if($data->trf_lain != NULL){
                $tarif = TarifLain::find($data->trf_lain);
                if($tarif != NULL){
                    $data['tarifLain'] = 'Rp. '. number_format($tarif->tarif);
                    $data['tarifLainId'] = $tarif->id;
                }
            }

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
        if(request()->ajax()){
            try{
                $rules = array(
                    'blok'     => 'required',
                    'los'      => ['required', 'regex:/^[a-zA-Z0-9\,\s]+$/u','min:1', 'max:50'],
                    'lokasi'   => ['max:50'],
                    'usaha'    => ['max:30'],
                );
    
                $error = Validator::make($request->all(), $rules);
    
                if($error->fails())
                {
                    return response()->json(['errors' => 'Gagal Mengambil Data']);
                }

                $id = LevindCrypt::decryptString($request->hidden_id);

                //deklarasi model
                $tempat = TempatUsaha::find($id);

                //blok
                $blok = $request->blok;
                $tempat->blok = $blok;
                
                //no_alamat
                $los = strtoupper($request->los);
                $tempat->no_alamat = $los;
                $alamat = $los;
                
                //jml_alamat
                $los = explode(",",$los);
                $tempat->jml_alamat = count($los);
                
                //kd_kontrol
                $kode = TempatUsaha::kode($blok,$los);
                $kodeLama = $tempat->kd_kontrol;
                $tempat->kd_kontrol = $kode;
                
                //bentuk_usaha
                if($request->usaha != null){
                    $tempat->bentuk_usaha = ucwords($request->usaha);
                    $usaha = ucwords($request->usaha);
                }
                else{
                    $tempat->bentuk_usaha = NULL;
                    $usaha = "usaha";
                }

                //lok_tempat
                $lokasi = $request->lokasi;
                if($lokasi != NULL){
                    $tempat->lok_tempat = $lokasi;
                }
                else{
                    $tempat->lok_tempat = NULL;
                }

                //id_pemilik
                $id_pemillik = $request->pemilik;
                $tempat->id_pemilik = $id_pemillik;

                // //id_pengguna
                $id_pengguna = $request->pengguna;
                $tempat->id_pengguna = $id_pengguna;

                //Fasilitas
                if(empty($request->air) == FALSE){
                    $tempat->trf_airbersih = 1;
                    $id_meteran_air = $request->meterAir;
                    $tempat->id_meteran_air = $id_meteran_air;

                    $meteran = AlatAir::find($id_meteran_air);
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();

                    $diskon = array();
                    if($request->radioAirBersih == "semua_airbersih"){
                        $tempat->dis_airbersih = NULL;
                    }
                    else if($request->radioAirBersih == 'dis_airbersih'){
                        if($request->persenDiskonAir != NULL){
                            $diskon['type'] = 'diskon';
                            $diskon['value'] = $request->persenDiskonAir;
                            $tempat->dis_airbersih = json_encode($diskon);
                        }
                        else{
                            $tempat->dis_airbersih = NULL;
                        }
                    }
                    else{
                        $pilihanDiskon = array('byr','beban','pemeliharaan','arkot','charge');
                        if($request->hanya != NULL){
                            $diskon['type'] = 'hanya';
                            $hanya = array();
                            $j = 0;
                            for($i=0; $i<count($pilihanDiskon); $i++){
                                if(in_array($pilihanDiskon[$i],$request->hanya)){
                                    if($pilihanDiskon[$i] == 'charge'){
                                        if($request->persenChargeAir != NULL){
                                            $persen = $request->persenChargeAir;
                                            $dari = $request->chargeAir;
                                            $value = $persen.','.$dari;
                                            $hanya[$j] = [$pilihanDiskon[$i] => $value];
                                        }
                                    }
                                    else{
                                        $hanya[$j] = $pilihanDiskon[$i];
                                    }
                                    $j++;
                                }
                            }
                            $diskon['value'] = $hanya;
                            $tempat->dis_airbersih = json_encode($diskon);
                        }
                        else{
                            $tempat->dis_airbersih = NULL;
                        }
                    }
                }
                else{
                    if($tempat->id_meteran_air !== NULL){
                        $meteran = AlatAir::find($tempat->id_meteran_air);
                        $meteran->stt_sedia = 0;
                        $meteran->stt_bayar = 0;
                        $meteran->save();
                    }

                    $tempat->trf_airbersih = NULL;
                    $tempat->id_meteran_air = NULL;
                    $tempat->dis_airbersih = NULL;
                }

                if(empty($request->listrik) == FALSE){
                    $tempat->trf_listrik = 1;
                    $id_meteran_listrik = $request->meterListrik;
                    $tempat->id_meteran_listrik = $id_meteran_listrik;
                
                    $meteran = AlatListrik::find($id_meteran_listrik);
                    $tempat->daya = $meteran->daya;
                    $meteran->stt_sedia = 1;
                    $meteran->stt_bayar = 1;
                    $meteran->save();
                    
                    if(empty($request->dis_listrik) == FALSE){
                        if($request->persenDiskonListrik == NULL){
                            $tempat->dis_listrik = 0;
                        }
                        else{
                            $tempat->dis_listrik = $request->persenDiskonListrik;
                        }
                    }
                    else{
                        $tempat->dis_listrik = NULL;
                    }
                }
                else{
                    if($tempat->id_meteran_listrik !== NULL){
                        $meteran = AlatListrik::find($tempat->id_meteran_listrik);
                        $meteran->stt_sedia = 0;
                        $meteran->stt_bayar = 0;
                        $meteran->save();
                    }

                    $tempat->trf_listrik = NULL;
                    $tempat->daya = NULL;
                    $tempat->id_meteran_listrik = NULL;
                    $tempat->dis_listrik = NULL;
                }

                if(empty($request->keamananipk) == FALSE){
                    $tarif = TarifKeamananIpk::where('tarif',$request->trfKeamananIpk)->select('id')->first();
                    $tempat->trf_keamananipk = $tarif->id;

                    if(empty($request->dis_keamananipk) == FALSE){
                        if($request->diskonKeamananIpk == NULL){
                            $tempat->dis_keamananipk = 0;
                        }
                        else{
                            $diskon = explode(',',$request->diskonKeamananIpk);
                            $diskon = implode('',$diskon);
                            $tempat->dis_keamananipk = $diskon;
                        }
                    }
                    else{
                        $tempat->dis_keamananipk = NULL;
                    }
                }
                else{
                    $tempat->trf_keamananipk = NULL;
                    $tempat->dis_keamananipk = NULL;
                }

                if(empty($request->kebersihan) == FALSE){
                    $tarif = TarifKebersihan::where('tarif',$request->trfKebersihan)->select('id')->first();
                    $tempat->trf_kebersihan = $tarif->id;

                    if(empty($request->dis_kebersihan) == FALSE){
                        if($request->diskonKebersihan == NULL){
                            $tempat->dis_kebersihan = 0;
                        }
                        else{
                            $diskon = explode(',',$request->diskonKebersihan);
                            $diskon = implode('',$diskon);
                            $tempat->dis_kebersihan = $diskon;
                        }
                    }
                    else{
                        $tempat->dis_kebersihan = NULL;
                    }
                }
                else{
                    $tempat->trf_kebersihan = NULL;
                    $tempat->dis_kebersihan = NULL;
                }

                if(empty($request->airkotor) == FALSE){
                    $tempat->trf_airkotor = $request->trfAirKotor;
                }
                else{
                    $tempat->trf_airkotor = NULL;
                }

                if(empty($request->lain) == FALSE){
                    $tempat->trf_lain = $request->trfLain;
                }
                else{
                    $tempat->trf_lain = NULL;
                }

                // stt_cicil / Metode Pembayaran
                $stt_cicil = $request->cicilan;
                if($stt_cicil == "0"){
                    $tempat->stt_cicil = 0; //Kontan
                }
                else if ($stt_cicil == "1"){
                    $tempat->stt_cicil = 1; //Cicil
                }

                // stt_tempat
                $stt_tempat = $request->status;
                if($stt_tempat == "1"){
                    $tempat->stt_tempat = 1;
                    $tempat->ket_tempat = NULL;
                }
                else if($stt_tempat == "2"){
                    $tempat->stt_tempat = 2;
                    $tempat->ket_tempat = $request->ket_tempat;
                }

                //Save Record Tempat Usaha Baru
                $tempat->save();

                return response()->json(['success' => 'Data Berhasil Diupdate']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Diupdate']);
                // return response()->json(['errors' => $e]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(request()->ajax()){
            $id = LevindCrypt::decryptString($id);

            $data = TempatUsaha::findOrFail($id);
            $listrikId = $data->id_meteran_listrik;
            $airId = $data->id_meteran_air;

            try{
                $exists = Tagihan::where([['kd_kontrol', $data->kd_kontrol],['stt_lunas',0]])->get();
                if($exists != "[]"){
                    $totalpublish = 0;
                    $totalunpublish = 0;
                    $i = 0;
                    foreach($exists as $d){
                        if($d->stt_publish == 1){
                            $totalpublish = $totalpublish + $d->sel_tagihan;
                        }
                        else{
                            $totalunpublish = $totalunpublish + $d->sel_tagihan;
                        }
                        $i++;
                    }
                    return response()->json(['errors' => "$data->kd_kontrol gagal dihapus, Rp.". number_format($totalpublish). ",- belum dibayar dan Rp.".number_format($totalunpublish).",- tagihan yang akan datang."]);
                }

                if($listrikId != NULL){
                    $alat = AlatListrik::find($listrikId);
                    if($alat != NULL){
                        $alat->stt_sedia = 0;
                        $alat->stt_bayar = 0;
                        $alat->save();
                    }
                }
                
                if($data->id_meteran_air != NULL){
                    $alat = AlatAir::find($airId);
                    if($alat != NULL){
                        $alat->stt_sedia = 0;
                        $alat->stt_bayar = 0;
                        $alat->save();
                    }
                }

                $kontrol = $data->kd_kontrol;
                $data->delete();
                return response()->json(['success' => "$kontrol telah dihapus."]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data gagal dihapus.']);
            }
        }
    }

    public function rekap(){
        return view('tempatusaha.rekap',[
            'dataset' => TempatUsaha::rekap(),
            'airAvailable'=>TempatUsaha::airAvailable(),
            'listrikAvailable'=>TempatUsaha::listrikAvailable(),
            'trfKeamananIpk'=>TempatUsaha::trfKeamananIpk(),
            'trfKebersihan'=>TempatUsaha::trfKebersihan(),
            'trfAirKotor'=>TempatUsaha::trfAirKotor(),
            'trfLain'=>TempatUsaha::trfLain()
        ]);
    }

    public function rekapdetail($blok){
        if(request()->ajax()){
            $data['total'] = count(TempatUsaha::where('blok',$blok)->get());
            $data['penggunalistrik'] = count(TempatUsaha::where([['blok',$blok],['trf_listrik','!=',NULL]])->get());
            $data['penggunaairbersih'] = count(TempatUsaha::where([['blok',$blok],['trf_airbersih','!=',NULL]])->get());
            $data['penggunakeamananipk'] = count(TempatUsaha::where([['blok',$blok],['trf_keamananipk','!=',NULL]])->get());
            $data['penggunakebersihan'] = count(TempatUsaha::where([['blok',$blok],['trf_kebersihan','!=',NULL]])->get());
            $data['potensilistrik'] = $data['total'] - $data['penggunalistrik'];
            $data['potensiairbersih'] = $data['total'] - $data['penggunaairbersih'];
            $data['potensikeamananipk'] = $data['total'] - $data['penggunakeamananipk'];
            $data['potensikebersihan'] = $data['total'] - $data['penggunakebersihan'];

            $data['rincian'] = TempatUsaha::where('blok',$blok)->orderBy('kd_kontrol','asc')->get();

            return response()->json(['result' => $data]);
        }
    }

    public function fasilitas($fas){
        $dataset = TempatUsaha::fasilitas($fas);
        if($fas == 'airbersih'){
            $fasilitas = 'Air Bersih';
        }
        else if($fas == 'listrik'){
            $fasilitas = 'Listrik';
        }
        else if($fas == 'keamananipk'){
            $fasilitas = 'Keamanan & IPK';
        }
        else if($fas == 'kebersihan'){
            $fasilitas = 'Kebersihan';
        }
        else if($fas == 'airkotor'){
            $fasilitas = 'Air Kotor';
        }
        else if($fas == 'diskon'){
            $fasilitas = 'Diskon / Bebas Bayar';
        }
        else if($fas == 'lain'){
            $fasilitas = 'Lain - Lain';
        }
        
        if($fas == 'diskon'){
            $view = 'tempatusaha.diskon';
        }
        else{
            $view = 'tempatusaha.fasilitas';
        }
        return view($view,[
            'dataset'   => $dataset,
            'fasilitas' => $fasilitas,
            'fas'       => $fas,
            'airAvailable'=>TempatUsaha::airAvailable(),
            'listrikAvailable'=>TempatUsaha::listrikAvailable(),
            'trfKeamananIpk'=>TempatUsaha::trfKeamananIpk(),
            'trfKebersihan'=>TempatUsaha::trfKebersihan(),
            'trfAirKotor'=>TempatUsaha::trfAirKotor(),
            'trfLain'=>TempatUsaha::trfLain()
        ]);
    }

    public function qr($id){
        $id = LevindCrypt::decryptString($id);
        
        $dataset = TempatUsaha::find($id);
        $kode = 'KODEKONTROL@'.$dataset->kd_kontrol;
        $kontrol = $dataset->kd_kontrol;

        return view('tempatusaha.qr',[
            'id'=>$id,
            'kode'=>$kode,
            'kontrol'=>$kontrol
        ]);
    }
}
