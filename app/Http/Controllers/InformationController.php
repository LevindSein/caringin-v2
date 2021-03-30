<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;
use Carbon\Carbon;

use App\Models\Information;

class InformationController extends Controller
{
    public function __construct()
    {
        $this->middleware('information');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            $data = Information::orderBy('tanggal','desc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#e74a3b;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('information.index');
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
            $rules = array(
                'ket_info' => 'required',
            );
    
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }

            try{
                $pengaruh = '';
                if(empty($request->admin) == FALSE){
                    $pengaruh .= "Admin, ";
                }
                if(empty($request->manajer) == FALSE){
                    $pengaruh .= "Manajer, ";
                }
                if(empty($request->keuangan) == FALSE){
                    $pengaruh .= "Keuangan, ";
                }
                if(empty($request->kasir) == FALSE){
                    $pengaruh .= "Kasir, ";
                }
                if(empty($request->nasabah) == FALSE){
                    $pengaruh .= "Nasabah, ";
                }

                $pengaruh = rtrim($pengaruh, ", ");
                
                $data = [
                    'tanggal'    => date("Y-m-d",strtotime(Carbon::now())),
                    'keterangan' => $request->ket_info,
                    'pengaruh'   => $pengaruh
                ];

                Information::create($data);
                
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
        if(request()->ajax()){
            $data = Information::find($id);
            
            $pengaruh = explode(", ", $data->pengaruh);

            if(in_array('Admin', $pengaruh))
                $data['admin'] = true;
            else
                $data['admin'] = false;

            if(in_array('Manajer', $pengaruh))
                $data['manajer'] = true;
            else
                $data['manajer'] = false;

            if(in_array('Keuangan', $pengaruh))
                $data['keuangan'] = true;
            else
                $data['keuangan'] = false;

            if(in_array('Kasir', $pengaruh))
                $data['kasir'] = true;
            else
                $data['kasir'] = false;

            if(in_array('Nasabah', $pengaruh))
                $data['nasabah'] = true;
            else
                $data['nasabah'] = false;

            $data['pengaruh'] = $pengaruh;

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
            $rules = array(
                'ket_info' => 'required',
            );
    
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
            }

            try{
                $pengaruh = '';
                if(empty($request->admin) == FALSE){
                    $pengaruh .= "Admin, ";
                }
                if(empty($request->manajer) == FALSE){
                    $pengaruh .= "Manajer, ";
                }
                if(empty($request->keuangan) == FALSE){
                    $pengaruh .= "Keuangan, ";
                }
                if(empty($request->kasir) == FALSE){
                    $pengaruh .= "Kasir, ";
                }
                if(empty($request->nasabah) == FALSE){
                    $pengaruh .= "Nasabah, ";
                }

                $pengaruh = rtrim($pengaruh, ", ");
                
                $data = [
                    'keterangan' => $request->ket_info,
                    'pengaruh'   => $pengaruh
                ];

                Information::find($request->hidden_id)->update($data);
                
                return response()->json(['success' => 'Data Berhasil Ditambah.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Ditambah.']);
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
            $data = Information::findOrFail($id);
            try{
                $data->delete();
                return response()->json(['success' => 'Data telah dihapus.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data gagal dihapus.']);
            }
        }
    }
}
