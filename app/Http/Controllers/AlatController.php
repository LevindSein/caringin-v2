<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;
use Validator;
use Exception;

use App\Models\AlatListrik;
use App\Models\AlatAir;
use App\Models\TempatUsaha;

class AlatController extends Controller
{
    public function __construct()
    {
        $this->middleware('alatmeter');
    }

    public function index(Request $request){
        if(request()->ajax())
        {
            $data = DB::table('meteran_listrik')->leftJoin('tempat_usaha','meteran_listrik.id','=','tempat_usaha.id_meteran_listrik')
            ->select('meteran_listrik.id as id', 'meteran_listrik.kode as kode', 'meteran_listrik.nomor as nomor', 'meteran_listrik.akhir as akhir', 'meteran_listrik.daya as daya', 'tempat_usaha.kd_kontrol as kd_kontrol');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') ==  'master'){
                        $button = '<a type="button" title="Print QR" name="qr" id="'.$data->id.'" fas="listrik" class="qr"><i class="fas fa-qrcode" style="color:#fd7e14;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="listrik" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->kode.'" fas="listrik" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#e74a3b;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->editColumn('akhir', function ($data) {
                    return number_format($data->akhir);
                })
                ->editColumn('daya', function ($data) {
                    return number_format($data->daya);
                })
                ->editColumn('kd_kontrol', function($data){
                    if($data->kd_kontrol == null) return '<span style="color:green;">idle</span>';
                    return $data->kd_kontrol;
                })
                ->rawColumns(['action', 'kd_kontrol'])
                ->make(true);
        }
        return view('utilities.alat-meter.index');
    }

    public function air(Request $request){
        if(request()->ajax())
        {
            $data = DB::table('meteran_air')->leftJoin('tempat_usaha','meteran_air.id','=','tempat_usaha.id_meteran_air')
            ->select('meteran_air.id as id', 'meteran_air.kode as kode', 'meteran_air.nomor as nomor', 'meteran_air.akhir as akhir', 'tempat_usaha.kd_kontrol as kd_kontrol');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Print QR" name="edit" id="'.$data->id.'" fas="air" class="qr"><i class="fas fa-qrcode fa-sm" style="color:#fd7e14;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="air" class="edit"><i class="fas fa-edit fa-sm" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->kode.'" fas="air" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#e74a3b;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->editColumn('akhir', function ($data) {
                    return number_format($data->akhir);
                })
                ->editColumn('kd_kontrol', function($data){
                    if($data->kd_kontrol == null) return '<span style="color:green;">idle</span>';
                    return $data->kd_kontrol;
                })
                ->rawColumns(['action', 'kd_kontrol'])
                ->make(true);
        }
    }

    public function store(Request $request){
        if(request()->ajax()){
            if($request->radioMeter == 'listrik'){
                $rules = array(
                    'standListrik' => 'required',
                    'dayaListrik'  => 'required'
                );
                $role = 'listrik';
            }
            else{
                $rules = array(
                    'standAir' => 'required'
                );
                $role = 'air';
            }

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }

            if($request->nomor == null){
                $nomor = NULL;
            }
            else{
                $nomor = $request->nomor;
                $nomor = strtoupper($nomor);
            }
            
            $dataset = array();
            $kode = str_shuffle('0123456789');
            $kode = substr($kode,0,5);

            try{
                if($request->radioMeter == 'listrik'){
                    $akhir = explode(',',$request->standListrik);
                    $akhir = implode('',$akhir);
                    $daya = explode(',',$request->dayaListrik);
                    $daya = implode('',$daya);
                    $data = [
                        'kode'      => 'ML'.$kode,
                        'nomor'     => $nomor,
                        'akhir'     => $akhir,
                        'daya'      => $daya,
                        'stt_sedia' => 0,
                        'stt_bayar' => 0
                    ];

                    AlatListrik::create($data);
                }

                if($request->radioMeter == 'air'){
                    $akhir = explode(',',$request->standAir);
                    $akhir = implode('',$akhir);
                    $data = [
                        'kode'      => 'MA'.$kode,
                        'nomor'     => $nomor,
                        'akhir'     => $akhir,
                        'stt_sedia' => 0,
                        'stt_bayar' => 0
                    ];

                    AlatAir::create($data);
                }
                $dataset['status'] = 'success';
                $dataset['message'] = 'Data Berhasil Ditambah';
                $dataset['role'] = $role;
                return response()->json(['result' => $dataset]);  
            }
            catch(\Exception $e){
                $dataset['status'] = 'error';
                $dataset['message'] = 'Data Gagal Ditambah';
                $dataset['role'] = $role;
                return response()->json(['result' => $dataset]);
            }
        }
    }

    public function edit($fasilitas, $id){
        if(request()->ajax())
        {
            if($fasilitas == 'listrik'){
                $data = AlatListrik::findOrFail($id);
                return response()->json(['result' => $data]);
            }
            if($fasilitas == 'air'){
                $data = AlatAir::findOrFail($id);
                return response()->json(['result' => $data]);
            }
        }
    }

    public function update(Request $request){
        if(request()->ajax()){
            if($request->radioMeter == 'listrik'){
                $rules = array(
                    'standListrik' => 'required',
                    'dayaListrik'  => 'required'
                );
                $role = 'listrik';
            }
            else{
                $rules = array(
                    'standAir' => 'required'
                );
                $role = 'air';
            }

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => 'Data Gagal Diupdate.']);
            }

            if($request->nomor == null){
                $nomor = NULL;
            }
            else{
                $nomor = $request->nomor;
                $nomor = strtoupper($nomor);
            }
            
            $dataset = array();

            try{
                if($request->radioMeter == 'listrik'){
                    $akhir = explode(',',$request->standListrik);
                    $akhir = implode('',$akhir);
                    $daya = explode(',',$request->dayaListrik);
                    $daya = implode('',$daya);
                    $data = [
                        'nomor'     => $nomor,
                        'akhir'     => $akhir,
                        'daya'      => $daya
                    ];

                    AlatListrik::whereId($request->hidden_id)->update($data);
                }

                if($request->radioMeter == 'air'){
                    $akhir = explode(',',$request->standAir);
                    $akhir = implode('',$akhir);
                    $data = [
                        'nomor'     => $nomor,
                        'akhir'     => $akhir
                    ];

                    AlatAir::whereId($request->hidden_id)->update($data);
                }
                $dataset['status'] = 'success';
                $dataset['message'] = 'Data Berhasil Diupdate';
                $dataset['role'] = $role;
                return response()->json(['result' => $dataset]);  
            }
            catch(\Exception $e){
                $dataset['status'] = 'error';
                $dataset['message'] = 'Data Gagal Diupdate';
                $dataset['role'] = $role;
                return response()->json(['result' => $dataset]);
            }
        }
    }

    public function destroy($fasilitas, $id){
        if(request()->ajax()){
            if($fasilitas == 'listrik'){
                $data = AlatListrik::find($id);
                $role = 'listrik';
            }
            else{
                $data = AlatAir::find($id);
                $role = 'air';
            }

            $dataset = array();
            try{
                $dataset['success'] = 'Data telah dihapus';
                $dataset['role'] = $role;
                $data->delete();
                return response()->json(['result' => $dataset]);
            }
            catch(\Exception $e){
                $dataset['errors'] = 'Data gagal dihapus';
                $dataset['role'] = '';
                return response()->json(['result' => $dataset]);
            }
        }
    }

    public function qr($fasilitas, $id){
        if($fasilitas == 'listrik'){
            $fasilitas = 'Listrik';
            $kode = AlatListrik::find($id);
            $kontrol = $kode->kode;
            $kode = 'IDENTITIY-BP3C-'.$kode->kode;
        }

        if($fasilitas == 'air'){
            $fasilitas = 'Air Bersih';
            $kode = AlatAir::find($id);
            $kontrol = $kode->kode;
            $kode = 'IDENTITY-BP3C-'.$kode->kode;
        }

        return view('utilities.alat-meter.qr',[
            'id'=>$id,
            'kode'=>$kode,
            'kontrol'=>$kontrol,
            'fasilitas'=>$fasilitas
        ]);
    }
}
