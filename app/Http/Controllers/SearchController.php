<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blok;
use App\Models\User;
use App\Models\TempatUsaha;
use App\Models\AlatAir;
use App\Models\AlatListrik;
use App\Models\Tagihan;
use App\Models\IndoDate;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function cariBlok(Request $request){
        $blok = [];
        // if ($request->has('q')) {
            $cariBlok = $request->q;
            $blok = Blok::select('id', 'nama')->where('nama', 'LIKE', '%'.$cariBlok.'%')->orderBy('nama','asc')->limit(50)->get();
        // }
        return response()->json($blok);
    }

    public function cariNasabah(Request $request){
        $nasabah = [];
        // if ($request->has('q')) {
            $cariNasabah = $request->q;
            $nasabah = User::select('id', 'nama', 'ktp')
            ->where('nama', 'LIKE', '%'.$cariNasabah.'%')
            ->orWhere('ktp', 'LIKE', '%'.$cariNasabah.'%')
            ->orderBy('nama','asc')
            ->limit(20)
            ->get();
        // }
        return response()->json($nasabah);
    }

    public function cariAlamat(Request $request){
        $alamat = [];
        // if ($request->has('q')) {
            $cariAlamat = $request->q;
            $alamat = TempatUsaha::select('id', 'kd_kontrol')->where('kd_kontrol', 'LIKE', '%'.$cariAlamat.'%')->orderBy('kd_kontrol','asc')->limit(20)->get();
        // }
        return response()->json($alamat);
    }

    public function cariAlamatKosong(Request $request){
        $alamat = [];
        // if ($request->has('q')) {
            $cariAlamat = $request->q;
            $alamat = TempatUsaha::select('id', 'kd_kontrol')->where([['kd_kontrol', 'LIKE', '%'.$cariAlamat.'%'],['stt_tempat',2]])->orderBy('kd_kontrol','asc')->get();
        // }
        return response()->json($alamat);
    }

    public function cariAlatAir(Request $request){
        $alat = [];
        // if ($request->has('q')) {
            $cariAlat = $request->q;
            $alat = AlatAir::where([['kode', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orWhere([['nomor', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orderBy('id','desc')
            ->limit(20)
            ->get();
        // }
        return response()->json($alat);
    }

    public function cariAlatListrik(Request $request){
        $alat = [];
        // if ($request->has('q')) {
            $cariAlat = $request->q;
            $alat = AlatListrik::where([['kode', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orWhere([['nomor', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orWhere([['daya', 'LIKE', '%'.$cariAlat.'%'],['stt_sedia',0]])
            ->orderBy('id','desc')
            ->limit(20)
            ->get();
        // }
        return response()->json($alat);
    }

    public function cariTagihan(Request $request, $id){
        if(request()->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_tagihan);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_tagihan);
            return response()->json(['result' => $result]);
        }
    }

    public function cariListrik(Request $request, $id){
        if(request()->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_listrik);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_listrik);
            return response()->json(['result' => $result]);
        }
    }

    public function cariAirBersih(Request $request, $id){
        if(request()->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_airbersih);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_airbersih);
            return response()->json(['result' => $result]);
        }
    }

    public function cariKeamananIpk(Request $request, $id){
        if(request()->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_keamananipk);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_keamananipk);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_keamananipk);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_keamananipk);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_keamananipk);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_keamananipk);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_keamananipk);
            return response()->json(['result' => $result]);
        }
    }

    public function cariKebersihan(Request $request, $id){
        if(request()->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_kebersihan);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_kebersihan);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_kebersihan);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_kebersihan);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_kebersihan);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_kebersihan);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_kebersihan);
            return response()->json(['result' => $result]);
        }
    }

    public function cariAirKotor(Request $request, $id){
        if(request()->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_airkotor);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_airkotor);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_airkotor);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_airkotor);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_airkotor);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_airkotor);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_airkotor);
            return response()->json(['result' => $result]);
        }
    }

    public function cariLain(Request $request, $id){
        if(request()->ajax()) {
            $result = array();

            $data = Tagihan::find($id);

            $bulan = strtotime($data->bln_tagihan);

            $result['kode']     = $data->kd_kontrol;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan->ttl_lain);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan->ttl_lain);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan->ttl_lain);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan->ttl_lain);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan->ttl_lain);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan->ttl_lain);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }

            $result['bulanini']      = IndoDate::bulan($data->bln_tagihan," ");
            $result['totalbulanini'] = number_format($data->ttl_lain);
            return response()->json(['result' => $result]);
        }
    }

    public function cariTagihanku(Request $request, $fasilitas, $kontrol){
        if(request()->ajax()) {
            $result = array();

            $bulan = strtotime(Carbon::now());
            $now   = date("Y-m",$bulan);
            $bulan = strtotime(date("Y-m",$bulan));

            $result['kode'] = $kontrol;

            $total = 0;
            
            $bulan1  = date("Y-m", strtotime("-6 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['bln_tagihan',$bulan1]])->first();
            if($tagihan != NULL){
                $result['bulan1']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan1'] = number_format($tagihan["sel_$fasilitas"]);
            }
            else{
                $result['bulan1']      = '';
                $result['totalbulan1'] = 0;
            }
            $total = $total + $tagihan["sel_$fasilitas"];

            $bulan2  = date("Y-m", strtotime("-5 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['bln_tagihan',$bulan2]])->first();
            if($tagihan != NULL){
                $result['bulan2']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan2'] = number_format($tagihan["sel_$fasilitas"]);
            }
            else{
                $result['bulan2']      = '';
                $result['totalbulan2'] = 0;
            }
            $total = $total + $tagihan["sel_$fasilitas"];

            $bulan3  = date("Y-m", strtotime("-4 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['bln_tagihan',$bulan3]])->first();
            if($tagihan != NULL){
                $result['bulan3']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan3'] = number_format($tagihan["sel_$fasilitas"]);
            }
            else{
                $result['bulan3']      = '';
                $result['totalbulan3'] = 0;
            }
            $total = $total + $tagihan["sel_$fasilitas"];
            
            $bulan4  = date("Y-m", strtotime("-3 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['bln_tagihan',$bulan4]])->first();
            if($tagihan != NULL){
                $result['bulan4']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan4'] = number_format($tagihan["sel_$fasilitas"]);
            }
            else{
                $result['bulan4']      = '';
                $result['totalbulan4'] = 0;
            }
            $total = $total + $tagihan["sel_$fasilitas"];

            $bulan5  = date("Y-m", strtotime("-2 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['bln_tagihan',$bulan5]])->first();
            if($tagihan != NULL){
                $result['bulan5']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan5'] = number_format($tagihan["sel_$fasilitas"]);
            }
            else{
                $result['bulan5']      = '';
                $result['totalbulan5'] = 0;
            }
            $total = $total + $tagihan["sel_$fasilitas"];

            $bulan6  = date("Y-m", strtotime("-1 month", $bulan));
            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['bln_tagihan',$bulan6]])->first();
            if($tagihan != NULL){
                $result['bulan6']      = IndoDate::bulan($tagihan->bln_tagihan," ");
                $result['totalbulan6'] = number_format($tagihan["sel_$fasilitas"]);
            }
            else{
                $result['bulan6']      = '';
                $result['totalbulan6'] = 0;
            }
            $total = $total + $tagihan["sel_$fasilitas"];

            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['bln_tagihan',$now]])->first();
            $result['bulanini']      = IndoDate::bulan($now," ");
            $result['totalbulanini'] = number_format($tagihan["sel_$fasilitas"]);
            $total = $total + $tagihan["sel_$fasilitas"];

            $result['totalselisih'] = number_format($total);
            return response()->json(['result' => $result]);
        }
    }
}
