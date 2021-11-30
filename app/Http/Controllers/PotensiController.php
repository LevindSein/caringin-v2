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
        return view('potensi.tagihan');
    }
}
