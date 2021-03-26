<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;

use App\Models\Blok;
use App\Models\TempatUsaha;
use App\Models\Tagihan;
use App\Models\Penghapusan;
use App\Models\Pembayaran;
use App\Models\PasangAlat;

class BlokController extends Controller
{
    public function __construct()
    {
        $this->middleware('blok');
    }

    public function index(Request $request){
        if(request()->ajax())
        {
            $data = Blok::orderBy('nama','asc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->nama.'" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->addColumn('jumlah', function($data){
                    $pengguna = TempatUsaha::where('blok',$data->nama)->count();
                    return $pengguna;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('utilities.blok.index');
    }

    public function store(Request $request){
        $rules = array(
            'blokInput' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }

        try{
            $blok = new Blok;
            $blok->nama = strtoupper($request->blokInput);
            $blok->save();

            return response()->json(['success' => 'Data Blok Berhasil Ditambah']);  
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Ditambah.']);
        }
    }

    public function edit($id){
        if(request()->ajax())
        {
            $data = Blok::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, Blok $blok){
        $rules = array(
            'blokInput' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => 'Data Gagal Diupdate.']);
        }

        try{
            $blok = Blok::find($request->hidden_id);
            $blokLama = $blok->nama;
            $nama = strtoupper($request->blokInput);
            $blok->nama = $nama;

            $blok->save();

            $tempat = TempatUsaha::where('blok',$blokLama)->get();
            $tagihan = Tagihan::where('blok',$blokLama)->get();
            $penghapusan = Penghapusan::where('blok',$blokLama)->get();
            $pembayaran = Pembayaran::where('blok',$blokLama)->get();
            $pasangalat = PasangAlat::where('blok',$blokLama)->get();
            
            if($tempat != NULL){
                foreach($tempat as $t){
                    $kontrol = $t->kd_kontrol;
                    $pattern = '/'.$blokLama.'/i';
                    $kontrol = preg_replace($pattern, $nama, $kontrol);
                    $t->kd_kontrol = $kontrol;
                    $t->blok = $nama;
                    $t->save();
                }
            }

            if($tagihan != NULL){
                foreach($tagihan as $t){
                    $kontrol = $t->kd_kontrol;
                    $pattern = '/'.$blokLama.'/i';
                    $kontrol = preg_replace($pattern, $nama, $kontrol);
                    $t->kd_kontrol = $kontrol;
                    $t->blok = $nama;
                    $t->save();
                }
            }

            if($penghapusan != NULL){
                foreach($penghapusan as $t){
                    $kontrol = $t->kd_kontrol;
                    $pattern = '/'.$blokLama.'/i';
                    $kontrol = preg_replace($pattern, $nama, $kontrol);
                    $t->kd_kontrol = $kontrol;
                    $t->blok = $nama;
                    $t->save();
                }
            }
            
            if($pembayaran != NULL){
                foreach($pembayaran as $t){
                    $kontrol = $t->kd_kontrol;
                    $pattern = '/'.$blokLama.'/i';
                    $kontrol = preg_replace($pattern, $nama, $kontrol);
                    $t->kd_kontrol = $kontrol;
                    $t->blok = $nama;
                    $t->save();
                }
            }

            if($pasangalat != NULL){
                foreach($pasangalat as $t){
                    $kontrol = $t->kd_kontrol;
                    $pattern = '/'.$blokLama.'/i';
                    $kontrol = preg_replace($pattern, $nama, $kontrol);
                    $t->kd_kontrol = $kontrol;
                    $t->blok = $nama;
                    $t->save();
                }
            }

            return response()->json(['success' => 'Data Berhasil Diupdate.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data Gagal Diupdate.']);
        }

    }

    public function destroy($id){
        try{
            $blok = Blok::find($id);
            $nama = $blok->nama;
            $pengguna = Tempatusaha::where('blok',$nama)->count();
            if($pengguna != 0 || $pengguna != NULL){
                return response()->json(['errors' => 'Data gagal dihapus.']);
            }
            else{
                $blok->delete();
                return response()->json(['success' => 'Data telah dihapus.']);
            }
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Data gagal dihapus.']);
        }
    }
}
