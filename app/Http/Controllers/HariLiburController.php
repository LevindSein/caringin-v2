<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;

use App\Models\HariLibur;
use App\Models\IndoDate;

use App\Models\LevindCrypt;

class HariLiburController extends Controller
{
    public function __construct()
    {
        $this->middleware('harilibur');
    }

    public function index(Request $request){
        if(request()->ajax())
        {
            $data = HariLibur::orderBy('id','desc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" data-toggle="tooltip" data-original-title="Edit Hari" name="edit" id="'.LevindCrypt::encryptString($data->id).'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Hapus Hari" name="delete" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->tanggal.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#e74a3b;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('utilities.hari-libur.index');
    }

    public function store(Request $request)
    {
        if(request()->ajax()){
            $rules = array(
                'tanggal' => 'required',
                'ket'     => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }

            $data = [
                'tanggal' => $request->tanggal,
                'ket'     => ucwords($request->ket),
            ];

            try{
                HariLibur::create($data);
                return response()->json(['success' => 'Data Tanggal Berhasil Ditambah']);  
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }
        }
    }

    public function edit($id){
        if(request()->ajax())
        {
            $id = LevindCrypt::decryptString($id);
            $data = HariLibur::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, HariLibur $harilibur)
    {
        if(request()->ajax()){
            $rules = array(
                'tanggal' => 'required',
                'ket'     => 'required'
            );

            $error = Validator::make($request->all(), $rules);

            if($error->fails())
            {
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }

            $data = [
                'tanggal' => $request->tanggal,
                'ket'     => ucwords($request->ket),
            ];

            try{
                $id = LevindCrypt::decryptString($request->hidden_id);
                HariLibur::find($id)->update($data);
                return response()->json(['success' => 'Data Berhasil Diupdate.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Diupdate.']);
            }
        }
    }

    public function destroy($id)
    {
        if(request()->ajax()){
            try{
                $id = LevindCrypt::decryptString($id);
                $tanggal = HariLibur::find($id);
                $data = $tanggal->tanggal;
                $tanggal->delete();
                return response()->json(['success' => "Data $data telah dihapus."]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data gagal dihapus.']);
            }
        }
    }
}
