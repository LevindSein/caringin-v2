<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;

use App\Models\User;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('settings');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.index');
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
            if(Session::get('role') == 'master'){
                $rules = array(
                    'nama'     => ['required', 'regex:/^[a-zA-Z\.\s]+$/u','min:2', 'max:30'],
                    'username' => 'required',
                    'password' => ['required','min:6', 'max:30'],
                );

                $error = Validator::make($request->all(), $rules);

                if($error->fails())
                {
                    return response()->json(['errors' => 'Gagal Mengambil Data.']);
                }

                $data = [
                    'ktp'      => NULL,
                    'nama'     => ucwords($request->nama),
                    'username' => strtolower($request->username),
                    'password' => sha1(md5(hash('gost',$request->password))),
                    'anggota'  => NULL,
                    'email'    => NULL,
                    'alamat'   => NULL,
                    'npwp'     => NULL,
                    'hp'       => NULL,
                ];
            }
            else{
                $rules = array(
                    'ktp'      => 'required',
                    'nama'     => ['required', 'regex:/^[a-zA-Z\.\s]+$/u','min:2', 'max:30'],
                    'username' => 'required',
                    'password' => ['required','min:6', 'max:30'],
                    'alamat'   => 'required',
                    'hp'       => 'required',
                );

                $error = Validator::make($request->all(), $rules);

                if($error->fails())
                {
                    return response()->json(['errors' => 'Gagal Mengambil Data.']);
                }

                $data = [
                    'ktp'      => $request->ktp,
                    'username' => strtolower($request->username),
                    'password' => sha1(md5(hash('gost',$request->password))),
                    'anggota'  => NULL,
                    'email'    => strtolower($request->email.'@gmail.com'),
                    'alamat'   => $request->alamat,
                    'npwp'     => NULL,
                    'hp'       => NULL,
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
            }

            try{
                User::find(Session::get('userId'))->update($data);
                return response()->json(['success' => 'Data Berhasil Diupdate.']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Diupdate.']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
