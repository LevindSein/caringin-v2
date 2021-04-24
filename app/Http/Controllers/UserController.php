<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use Exception;

use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('user');
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
            $data = User::where('role','admin')->orderBy('nama','asc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" data-toggle="tooltip" data-original-title="Otoritas" name="otoritas" id="'.$data->id.'" nama="'.$data->nama.'" class="otoritas"><i class="fas fa-hand-point-up" style="color:#36b9cc;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Reset Password" name="reset" id="'.$data->id.'" nama="'.$data->nama.'" class="reset"><i class="fas fa-key" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->nama.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->editColumn('otoritas', function ($data) {
                    if ($data->otoritas == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                    else return '<span class="text-center"><i class="fas fa-check fa-sm"></i></span>';
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->nama.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns(['action','show','otoritas'])
                ->make(true);
        }
        return view('user.index');
    }

    public function manajer(Request $request){
        if(request()->ajax()){
            $data = User::where('role','manajer')->orderBy('nama','asc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" data-toggle="tooltip" data-original-title="Reset Password" name="reset" id="'.$data->id.'" nama="'.$data->nama.'" class="reset"><i class="fas fa-key" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->nama.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->nama.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns(['action','show'])
                ->make(true);
        }
    }

    public function keuangan(Request $request){
        if(request()->ajax()){
            $data = User::where('role','keuangan')->orderBy('nama','asc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" data-toggle="tooltip" data-original-title="Reset Password" name="reset" id="'.$data->id.'" nama="'.$data->nama.'" class="reset"><i class="fas fa-key" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->nama.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->nama.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns(['action','show'])
                ->make(true);
        }
    }

    public function kasir(Request $request){
        if(request()->ajax()){
            $data = User::where('role','kasir')->orderBy('nama','asc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" data-toggle="tooltip" data-original-title="Otoritas" name="otoritas" id="'.$data->id.'" nama="'.$data->nama.'" class="otoritas-kasir"><i class="fas fa-hand-point-up" style="color:#36b9cc;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Reset Password" name="reset" id="'.$data->id.'" nama="'.$data->nama.'" class="reset"><i class="fas fa-key" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->nama.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->nama.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns(['action','show'])
                ->make(true);
        }
    }

    public function nasabah(Request $request){
        if(request()->ajax()){
            $data = User::where('role','nasabah')->orderBy('nama','asc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '<a type="button" data-toggle="tooltip" data-original-title="Reset Password" name="reset" id="'.$data->id.'" nama="'.$data->nama.'" class="reset"><i class="fas fa-key" style="color:#fd7e14;"></i></a>';
                    $button .= '&nbsp;&nbsp;<a type="button" data-toggle="tooltip" data-original-title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->nama.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    return $button;
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->nama.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns(['action','show'])
                ->make(true);
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
                'ktp'      => 'required',
                'nama'     => ['required', 'regex:/^[a-zA-Z\.\s]+$/u','min:1', 'max:30'],
                'username' => 'required',
                'password' => 'required',
                'hp'       => 'required',
                'role'     => 'required',
            );

            $error = Validator::make($request->all(), $rules);

            $dataset = array();

            if($error->fails())
            {
                $dataset['status'] = 'error';
                $dataset['message'] = 'Data Gagal Ditambah';
                return response()->json(['result' => $dataset]);
            }

            $data = [
                'ktp'      => $request->ktp,
                'nama'     => ucwords($request->nama),
                'username' => strtolower($request->username),
                'password' => sha1(md5(hash('gost',$request->password))),
                'email'    => strtolower($request->email.'@gmail.com'),
                'role'     => $request->role,
            ];
        
            if($request->email == NULL) {
                $data['email'] = NULL;
            }
            
            if($request->hp[0] == '0') {
                $hp = '62'.substr($request->hp,1);
                $data['hp'] = $hp;
            }
            else{
                $hp = '62'.$request->hp;
                $data['hp'] = $hp;
            }

            try{
                $dataset['status'] = 'success';
                $dataset['message'] = 'Data Berhasil Ditambah';
                $dataset['role'] = $request->role;
                User::create($data);

                return response()->json(['result' => $dataset]);
            }
            catch(\Exception $e){
                $dataset['status'] = 'error';
                $dataset['message'] = 'Data Gagal Ditambah';
                return response()->json(['result' => $dataset]);
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
            $data = User::findOrFail($id);
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
            $data = User::findOrFail($id);
            if($data->email != NULL){
                $email = substr($data->email, 0, strpos($data->email, '@'));
                $data['email'] = $email;
            }
            if($data->hp != NULL){
                $hp = substr($data->hp,2);
                $data['hp'] = $hp;
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
    public function update(Request $request, User $user)
    {
        if(request()->ajax()){
            $rules = array(
                'ktp'      => 'required',
                'nama'     => ['required', 'regex:/^[a-zA-Z\.\s]+$/u','min:1', 'max:30'],
                'username' => 'required',
                'hp'       => 'required',
                'role'     => 'required',
            );

            $error = Validator::make($request->all(), $rules);

            $dataset = array();

            if($error->fails())
            {
                $dataset['status'] = 'error';
                $dataset['message'] = 'Data Gagal Diupdate';
                return response()->json(['result' => $dataset]);
            }

            $data = [
                'ktp'      => $request->ktp,
                'nama'     => ucwords($request->nama),
                'username' => strtolower($request->username),
                'email'    => strtolower($request->email.'@gmail.com'),
                'role'     => $request->role,
            ];
        
            if($request->email == NULL) {
                $data['email'] = NULL;
            }
            
            if($request->hp[0] == '0') {
                $hp = '62'.substr($request->hp,1);
                $data['hp'] = $hp;
            }
            else{
                $hp = '62'.$request->hp;
                $data['hp'] = $hp;
            }

            try{
                $dataset['status'] = 'success';
                $dataset['message'] = 'Data Berhasil Diupdate';
                $dataset['role'] = $request->role;

                User::whereId($request->hidden_id)->update($data);

                if($request->role != 'admin'){
                    $user = User::find($request->hidden_id);
                    $user->otoritas = NULL;
                    $user->save();
                }

                return response()->json(['result' => $dataset]);
            }
            catch(\Exception $e){
                $dataset['status'] = 'error';
                $dataset['message'] = 'Data Gagal Diupdate';
                return response()->json(['result' => $dataset]);
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
            $data = User::findOrFail($id);
            $dataset = array();
            try{
                $role = $data->role;
                $dataset['success'] = "Data $data->nama telah dihapus";
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
    
    /**
     * Reset the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request,$id)
    {
        if(request()->ajax()){
            try{
                $pass = str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789');
                $pass = substr($pass,0,8);

                $password = sha1(md5(hash('gost',$pass)));

                User::findOrFail($id)->update([
                    'password' => $password
                ]);
                return response()->json(['success' => $pass]);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Oops! Something wrong']);
            }
        }
    }

    /**
     * Show the form for editing authority the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function etoritas($id)
    {
        if(request()->ajax())
        {
            $data = User::find($id);

            if($data->otoritas == NULL){
                $data['blok'] = NULL;
            }
            else{
                $otoritas  = json_decode($data->otoritas);
                
                if(isset($otoritas->otoritas))
                    $data['bloks'] = $otoritas->otoritas;
                else
                    $data['bloks'] = false;

                if(isset($otoritas->pedagang))
                    $data['pedagang'] = $otoritas->pedagang;
                else
                    $data['pedagang'] = false;

                if(isset($otoritas->tempatusaha))
                    $data['tempatusaha'] = $otoritas->tempatusaha;
                else
                    $data['tempatusaha'] = false;
                
                if(isset($otoritas->tagihan))
                    $data['tagihan'] = $otoritas->tagihan;
                else
                    $data['tagihan'] = false;
                
                if(isset($otoritas->blok))
                    $data['blok'] = $otoritas->blok;
                else
                    $data['blok'] = false;
                
                if(isset($otoritas->pemakaian))
                    $data['pemakaian'] = $otoritas->pemakaian;
                else
                    $data['pemakaian'] = false;
                
                if(isset($otoritas->pendapatan))
                    $data['pendapatan'] = $otoritas->pendapatan;
                else
                    $data['pendapatan'] = false;
                
                if(isset($otoritas->tunggakan))
                    $data['tunggakan'] = $otoritas->tunggakan;
                else
                    $data['tunggakan'] = false;
                
                if(isset($otoritas->datausaha))
                    $data['datausaha'] = $otoritas->datausaha;
                else
                    $data['datausaha'] = false;
            
                if(isset($otoritas->publish))
                    $data['publish'] = $otoritas->publish;
                else
                    $data['publish'] = false;
                
                if(isset($otoritas->alatmeter))
                    $data['alatmeter'] = $otoritas->alatmeter;
                else
                    $data['alatmeter'] = false;
                
                if(isset($otoritas->tarif))
                    $data['tarif'] = $otoritas->tarif;
                else
                    $data['tarif'] = false;
                
                if(isset($otoritas->harilibur))
                    $data['harilibur'] = $otoritas->harilibur;
                else
                    $data['harilibur'] = false;
                
                if(isset($otoritas->layanan))
                    $data['layanan'] = $otoritas->layanan;
                else
                    $data['layanan'] = false;
                
                if(isset($otoritas->simulasi))
                    $data['simulasi'] = $otoritas->simulasi;
                else
                    $data['simulasi'] = false;
            }

            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update Authority the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function otoritas(Request $request)
    {
        if(request()->ajax()){
            $pilihanKelola = array('pedagang','tempatusaha','tagihan','blok','pemakaian','pendapatan','tunggakan','datausaha','publish','alatmeter','tarif','harilibur','layanan','simulasi');

            $kelola = array();
            $kelola['otoritas'] = $request->blokOtoritas;

            try{
                if($request->blokOtoritas != NULL){
                    for($i=0; $i<count($pilihanKelola); $i++){
                        if(in_array($pilihanKelola[$i],$request->kelola)){
                            $kelola[$pilihanKelola[$i]] = true;
                        }
                        else{
                            $kelola[$pilihanKelola[$i]] = false;
                        }
                    }
            
                    $json = json_encode($kelola);
                }
                else{
                    $json = NULL;
                }
                $data = User::find($request->hidden_id_otoritas);
                $data->otoritas = $json;
                $data->save();
                return response()->json(['success' => 'Otoritas Berhasil Diupdate.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Otoritas Gagal Diupdate.']);
            }
        }
    }

    public function kasirEtoritas($id)
    {
        if(request()->ajax())
        {
            $data = User::find($id);

            if($data->otoritas == NULL){
                $data['kepala'] = false;
                $data['lapangan'] = false;
            }
            else{
                $otoritas  = json_decode($data->otoritas);
                $data['kepala'] = $otoritas->kepala_kasir;
                $data['lapangan'] = $otoritas->lapangan_kasir;
            }

            return response()->json(['result' => $data]);
        }
    }

    public function kasirOtoritas(Request $request)
    {
        if(request()->ajax()){
            $pilihanKelola = array('kepala_kasir','lapangan_kasir');

            try{
                for($i=0; $i<count($pilihanKelola); $i++){
                    if(in_array($pilihanKelola[$i],$request->kelola_kasir)){
                        $kelola[$pilihanKelola[$i]] = true;
                    }
                    else{
                        $kelola[$pilihanKelola[$i]] = false;
                    }
                }

                $json = json_encode($kelola);

                $data = User::find($request->hidden_id_kasir);
                $data->otoritas = $json;
                $data->save();
                return response()->json(['success' => 'Otoritas Berhasil Diupdate.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Otoritas Gagal Diupdate.']);
            }
        }
    }
}
