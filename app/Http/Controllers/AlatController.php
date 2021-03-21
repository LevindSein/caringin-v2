<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        if($request->ajax())
        {
            $data = AlatListrik::orderBy('id','desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if(Session::get('role') ==  'master'){
                        $button = '<a type="button" title="Print QR" name="qr" id="'.$data->id.'" fas="listrik" class="qr"><i class="fas fa-qrcode" style="color:#fd7e14;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="listrik" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" fas="listrik" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->addColumn('tempat', function($data){
                    $tempat = TempatUsaha::where('id_meteran_listrik',$data->id)->select('kd_kontrol')->first();
                    if($tempat == null) return '<span class="text-center" style="color:#1cc88a;">idle</span>';
                    else return $tempat['kd_kontrol'];
                })
                ->editColumn('akhir', function ($data) {
                    return number_format($data->akhir);
                })
                ->editColumn('daya', function ($data) {
                    return number_format($data->daya);
                })
                ->rawColumns(['action','tempat'])
                ->make(true);
        }
        return view('alatmeter.index');
    }

    public function air(Request $request){
        if($request->ajax())
        {
            $data = AlatAir::orderBy('id','desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Print QR" name="edit" id="'.$data->id.'" fas="air" class="qr"><i class="fas fa-qrcode fa-sm" style="color:#fd7e14;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="air" class="edit"><i class="fas fa-edit fa-sm" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" fas="air" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->addColumn('tempat', function($data){
                    $tempat = TempatUsaha::where('id_meteran_air',$data->id)->select('kd_kontrol')->first();
                    if($tempat == null) return '<span class="text-center" style="color:#1cc88a;">idle</span>';
                    else return $tempat['kd_kontrol'];
                })
                ->editColumn('akhir', function ($data) {
                    return number_format($data->akhir);
                })
                ->rawColumns(['action','tempat'])
                ->make(true);
        }
    }

    public function store(Request $request){
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

    public function destroy($fasilitas, $id){
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
            $dataset['status'] = 'Data telah dihapus';
            $dataset['role'] = $role;
            $data->delete();
            return response()->json(['result' => $dataset]);
        }
        catch(\Exception $e){
            $dataset['status'] = 'Data gagal dihapus';
            return response()->json(['result' => $dataset]);
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

        return view('alatmeter.qr',[
            'id'=>$id,
            'kode'=>$kode,
            'kontrol'=>$kontrol,
            'fasilitas'=>$fasilitas
        ]);
    }
}
