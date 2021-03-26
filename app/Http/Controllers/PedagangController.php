<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;

use App\Models\Pedagang;
use App\Models\TempatUsaha;

class PedagangController extends Controller
{
    public function __construct()
    {
        $this->middleware('pedagang');
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
            $data = Pedagang::where('role','nasabah');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->nama.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.$data->id.'" nama="'.$data->nama.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns(['action','show','ktp','email','hp'])
                ->make(true);
        }
        return view('pedagang.index');
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
        $rules = array(
            'ktp'      => 'required',
            'nama'     => ['required', 'regex:/^[a-zA-Z\.\s]+$/u','min:2', 'max:30'],
            'username' => 'required',
            'anggota'  => 'required',
            'hp'       => 'required',
            'alamat'   => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }

        $data = [
            'ktp'      => $request->ktp,
            'nama'     => ucwords($request->nama),
            'username' => strtolower($request->username),
            'password' => sha1(md5(hash('gost','1234567'))),
            'anggota'  => strtoupper($request->anggota),
            'email'    => strtolower($request->email.'@gmail.com'),
            'alamat'   => $request->alamat,
            'role'     => 'nasabah',
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
            Pedagang::create($data);

            $ped = Pedagang::where('username',$request->username)->first();

            if($request->pemilik == "pemilik"){
                $alamatPemilik = $request->alamatPemilik;
                foreach($alamatPemilik as $alamat){
                    $tempat = TempatUsaha::find($alamat);
                    if($tempat != NULL){
                        $tempat->id_pemilik = $ped->id;
                        $tempat->save();
                    }
                }
            }

            if($request->pengguna == "pengguna"){
                $alamatPengguna = $request->alamatPengguna;
                foreach($alamatPengguna as $alamat){
                    $tempat = TempatUsaha::find($alamat);
                    if($tempat != NULL){
                        $tempat->id_pengguna = $ped->id;
                        $tempat->save();
                    }
                }
            }
            return response()->json(['success' => 'Data Berhasil Ditambah.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
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
            $data = Pedagang::findOrFail($id);
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
            $data = Pedagang::findOrFail($id);
            if($data->email != NULL){
                $email = substr($data->email, 0, strpos($data->email, '@'));
                $data['email'] = $email;
            }
            if($data->hp != NULL){
                $hp = substr($data->hp,2);
                $data['hp'] = $hp;
            }
            
            $data['pemilik'] = 0;
            $data['checkPemilik'] = 'unchecked';
            $data['pengguna'] = 0;
            $data['checkPengguna'] = 'unchecked';
            $pemilik = Pedagang::nasabah($id,'pemilik');
            if($pemilik != NULL){
                $data['pemilik'] = $pemilik;
                $data['checkPemilik'] = 'checked';
            }

            $pengguna = Pedagang::nasabah($id,'pengguna');
            if($pengguna != NULL){
                $data['pengguna'] = $pengguna;
                $data['checkPengguna'] = 'checked';
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
    public function update(Request $request, Pedagang $pedagang)
    {
        $rules = array(
            'ktp'      => 'required',
            'nama'     => ['required', 'regex:/^[a-zA-Z\.\s]+$/u','min:2', 'max:30'],
            'hp'       => 'required',
            'alamat'   => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Diedit.']);
        }

        $data = [
            'ktp'      => $request->ktp,
            'nama'     => ucwords($request->nama),
            'email'    => strtolower($request->email.'@gmail.com'),
            'alamat'   => $request->alamat,
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
            Pedagang::whereId($request->hidden_id)->update($data);

            $ped = Pedagang::find($request->hidden_id);

            if($request->pemilik == "pemilik"){
                $alamatPemilik = $request->alamatPemilik;

                $tempat = TempatUsaha::where('id_pemilik',$request->hidden_id)->get();
                if($tempat != NULL){
                    foreach($tempat as $t){
                        if(in_array($t->kd_kontrol, $alamatPemilik) == FALSE){
                            $hapus = TempatUsaha::find($t->id);
                            $hapus->id_pemilik = NULL;
                            $hapus->save();
                        }
                    }   
                }
                
                foreach($alamatPemilik as $alamat){
                    $tempat = TempatUsaha::find($alamat);
                    if($tempat != NULL){
                        $tempat->id_pemilik = $ped->id;
                        $tempat->save();
                    }
                }
            }
            else{
                $tempat = TempatUsaha::where('id_pemilik',$request->hidden_id)->get();
                if($tempat != NULL){
                    foreach($tempat as $t){
                        $hapus = TempatUsaha::find($t->id);
                        $hapus->id_pemilik = NULL;
                        $hapus->save();
                    }   
                }
            }

            if($request->pengguna == "pengguna"){
                $alamatPengguna = $request->alamatPengguna;

                $tempat = TempatUsaha::where('id_pengguna',$request->hidden_id)->get();
                if($tempat != NULL){
                    foreach($tempat as $t){
                        if(in_array($t->kd_kontrol, $alamatPengguna) == FALSE){
                            $hapus = TempatUsaha::find($t->id);
                            $hapus->id_pengguna = NULL;
                            $hapus->save();
                        }
                    }   
                }

                foreach($alamatPengguna as $alamat){
                    $tempat = TempatUsaha::find($alamat);
                    if($tempat != NULL){
                        $tempat->id_pengguna = $ped->id;
                        $tempat->save();
                    }
                }
            }
            else{
                $tempat = TempatUsaha::where('id_pengguna',$request->hidden_id)->get();
                if($tempat != NULL){
                    foreach($tempat as $t){
                        $hapus = TempatUsaha::find($t->id);
                        $hapus->id_pengguna = NULL;
                        $hapus->save();
                    }   
                }
            }
            return response()->json(['success' => 'Data Berhasil Diedit.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Diedit.']);
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
        $data = Pedagang::findOrFail($id);
        try{
            $data->delete();
            return response()->json(['success' => 'Data telah dihapus.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data gagal dihapus.']);
        }
    }
}
