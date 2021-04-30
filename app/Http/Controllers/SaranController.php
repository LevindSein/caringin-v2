<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;
use Carbon\Carbon;

use App\Models\IndoDate;
use App\Models\LevindCrypt;

use App\Models\Saran;

class SaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('saran');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::get('role') == 'master'){
            if(request()->ajax()){
                $data = Saran::orderBy('id','desc');
                return DataTables::of($data)
                    ->addColumn('action', function($data){
                        if($data->email != NULL)
                            $button = '<a target="_blank" href="mailto:'.$data->email.'?subject=Konfirmasi untuk '.$data->nama.'&body=Halo '.$data->nama.', terimakasih telah memberikan saran kepada kami. Terkait dengan saran anda %22'.$data->keterangan.'%22 pada tanggal '.IndoDate::tanggal($data->tanggal," ").'. Berikut Konfirmasi dari kami :" type="button" data-toggle="tooltip" data-original-title="Kirim Email" name="kirim_email" id="'.$data->id.'" class="kirim_email"><i class="fas fa-envelope" style="color:#e74a3b;"></i></a>';
                        else
                            $button = '<a disabled type="button" name="kirim_email" id="'.LevindCrypt::encryptString($data->id).'" class="kirim_email"><i class="fas fa-envelope" style="color:black;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a target="_blank" href="https://api.whatsapp.com/send?phone='.$data->hp.'&text=*Halo '.$data->nama.'*, terimakasih telah memberikan saran kepada kami. Terkait dengan saran anda *'.$data->keterangan.'* pada tanggal '.IndoDate::tanggal($data->tanggal," ").'. Berikut Konfirmasi dari kami :" type="button" data-toggle="tooltip" data-original-title="Kirim Whatsapp" name="kirim_whatsapp" id="'.$data->id.'" class="kirim_whatsapp"><i class="fas fa-phone-square-alt" style="color:#2dce89;"></i></a>';
                        return $button;
                    })
                    ->editColumn('status', function($data){
                        if($data->status == 0)
                            $button = '<button type="button" data-toggle="tooltip" data-original-title="Unsolved" name="confirm" id="'.LevindCrypt::encryptString($data->id).'" class="confirm btn btn-sm btn-danger"><i class="fas fa-times"></i></button>';
                        else if($data->status == 1)
                            $button = '<button type="button" data-toggle="tooltip" data-original-title="Done" name="confirm" id="'.LevindCrypt::encryptString($data->id).'" class="confirm btn btn-sm btn-success"><i class="fas fa-check"></i></button>';
                        else
                            $button = '<button type="button" data-toggle="tooltip" data-original-title="Unconfirmed" name="confirm" id="'.LevindCrypt::encryptString($data->id).'" class="confirm btn btn-sm btn-info"><i class="fas fa-question"></i></button>';
                        return $button;
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
            }
        }
        return view("saran.index");
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
            'nama'       => ['required', 'regex:/^[a-zA-Z\.\s]+$/u','min:2', 'max:30'],
            'hp'         => ['required', 'regex:/^[0-9]+$/u', 'min:1', 'max:13'],
            'email'      => ['max:20'],
            'keterangan' => ['required', 'min:1'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Dikirim.']);
        }

        $data = [
            'tanggal'    => date("Y-m-d",strtotime(Carbon::now())),
            'nama'       => ucwords($request->nama)." (".Session::get('username').")",
            'email'      => strtolower($request->email.'@gmail.com'),
            'keterangan' => $request->keterangan,
            'status'     => 0,
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
            Saran::create($data);
            return response()->json(['success' => 'Saran Berhasil Dikirim.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Saran Gagal Dikirim.']);
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

    public function confirm($id)
    {
        if(request()->ajax()){
            try{
                $id = LevindCrypt::decryptString($id);
                $data = Saran::find($id);
                if($data->status == 1){
                    $hasil = 0;
                }
                else if($data->status === 0){
                    $hasil = 1;
                }
                else{
                    $hasil = 0;
                }
                $data->status = $hasil;
                $data->save();
                if($hasil == 0)
                    return response()->json(['errors' => 'Problem Unsolved']);
                else
                    return response()->json(['success' => 'Problem Solved']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Gagal Mengambil Data.']);
            }
        }
    }
}
