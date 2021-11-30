<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TempatAktif;
use App\Exports\TempatPasif;
use App\Exports\TempatBebasBayar;

use DataTables;

use App\Models\LevindCrypt;
use App\Models\Tagihan;

class PotensiController extends Controller
{
    public function tempatUsaha(Request $request){
        if($request->ajax()){
            $data = DB::table('tempat_usaha')->where('stt_tempat', $request->get('data'))->leftJoin('user','tempat_usaha.id_pengguna','=','user.id')
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
                else if($data->stt_tempat == 3)
                    return '<span style="color:blue;">'.$data->kd_kontrol.'</span>';
                else
                    return '<span>'.$data->kd_kontrol.'</span>';
            })
            ->editColumn('id_pengguna', function($data){
                if($data->nama == null) return '<span style="color:green;">idle</span>';
                return $data->nama;
            })
            ->rawColumns([
                'show',
                'jml_alamat',
                'id_pengguna',
                'kd_kontrol'])
            ->make(true);
        }
        return view('potensi.tempatusaha');
    }

    public function tempatUsahaDownload(Request $request){
        $stt_tempat = $request->stt_tempat;

        if($stt_tempat == 1){
            return Excel::download(new TempatAktif, '(AKTIF) Tempat_Usaha_BP3C.xlsx');
        }
        else if($stt_tempat == 2){
            return Excel::download(new TempatPasif, '(PASIF) Tempat_Usaha_BP3C.xlsx');
        }
        else{
            return Excel::download(new TempatBebasBayar, '(BEBAS BAYAR) Tempat_Usaha_BP3C.xlsx');
        }
    }

    public function tagihan(){
        if(request()->ajax()){
            $data = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]])->orderBy('kd_kontrol','asc');
            return DataTables::of($data)
            ->editColumn('ttl_listrik', function ($data) {
                $hasil = number_format($data->ttl_listrik);
                if ($data->ttl_listrik == 0 && $data->stt_listrik === NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="No data, click more!" class="totallistrik" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                else if ($data->ttl_listrik == 0 && $data->stt_listrik !== NULL)
                    return '<a href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-original-title="Dy: '.number_format($data->daya_listrik).'<br>Aw: '.number_format($data->awal_listrik).'<br>Ak: '.number_format($data->akhir_listrik).'<br>Pk: '.number_format($data->pakai_listrik).'<hr>Click more!" class="totallistrik" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">0</span></a>';
                else {
                    return '<a href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-original-title="Dy: '.number_format($data->daya_listrik).'<br>Aw: '.number_format($data->awal_listrik).'<br>Ak: '.number_format($data->akhir_listrik).'<br>Pk: '.number_format($data->pakai_listrik).'<hr>Click more!" class="totallistrik" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                }
            })
            ->editColumn('ttl_airbersih', function ($data) {
                $hasil = number_format($data->ttl_airbersih);
                if ($data->ttl_airbersih == 0 && $data->stt_airbersih === NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="No data, click more!" class="totalairbersih" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                else if ($data->ttl_airbersih == 0 && $data->stt_airbersih !== NULL)
                    return '<a href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-original-title="Aw: '.number_format($data->awal_airbersih).'<br>Ak: '.number_format($data->akhir_airbersih).'<br>Pk: '.number_format($data->pakai_airbersih).'<hr>Click more!" class="totalairbersih" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">0</span></a>';
                else {
                    return '<a href="javascript:void(0)" data-html="true" data-toggle="tooltip" data-original-title="Aw: '.number_format($data->awal_airbersih).'<br>Ak: '.number_format($data->akhir_airbersih).'<br>Pk: '.number_format($data->pakai_airbersih).'<hr>Click more!" class="totalairbersih" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                }
            })
            ->editColumn('ttl_keamananipk', function ($data) {
                $hasil = number_format($data->ttl_keamananipk);
                if ($data->ttl_keamananipk == 0 && $data->stt_keamananipk === NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="No data, click more!" class="totalkeamananipk" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                else if ($data->ttl_keamananipk == 0 && $data->stt_keamananipk !== NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totalkeamananipk" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">0</span></a>';
                else
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totalkeamananipk" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
            })
            ->editColumn('ttl_kebersihan', function ($data) {
                $hasil = number_format($data->ttl_kebersihan);
                if ($data->ttl_kebersihan == 0 && $data->stt_kebersihan === NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="No data, click more!" class="totalkebersihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                else if ($data->ttl_kebersihan == 0 && $data->stt_kebersihan !== NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totalkebersihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">0</span></a>';
                else
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totalkebersihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
            })
            ->editColumn('ttl_airkotor', function ($data) {
                $hasil = number_format($data->ttl_airkotor);
                if ($data->ttl_airkotor == 0 && $data->stt_airkotor === NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="No data, click more!" class="totalairkotor" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                else if ($data->ttl_airkotor == 0 && $data->stt_airkotor !== NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totalairkotor" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">0</span></a>';
                else
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totalairkotor" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
            })
            ->editColumn('ttl_lain', function ($data) {
                $hasil = number_format($data->ttl_lain);
                if ($data->ttl_lain == 0 && $data->stt_lain === NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="No data, click more!" class="totallain" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                else if ($data->ttl_lain == 0 && $data->stt_lain !== NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totallain" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">0</span></a>';
                else
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totallain" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
            })
            ->editColumn('ttl_tagihan', function ($data) {
                $hasil = number_format($data->ttl_tagihan);
                $warna = max($data->warna_airbersih, $data->warna_listrik);
                if ($data->ttl_tagihan === NULL)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totaltagihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;"><i class="fas fa-times fa-sm"></i></span></a>';
                else if ($data->ttl_tagihan === 0)
                    return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totaltagihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">0</span></a>';
                else {
                    if($warna == 1 || $warna == 2)
                        return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totaltagihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#c4b71f;">'.$hasil.'</span></a>';
                    else if($warna == 3)
                        return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totaltagihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#e74a3b;">'.$hasil.'</span></a>';
                    else
                        return '<a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Click more!" class="totaltagihan" id="'.LevindCrypt::encryptString($data->id).'"><span style="color:#172b4d;">'.$hasil.'</span></a>';
                }
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns([
                'show',
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
        return view('potensi.tagihan');
    }
}
