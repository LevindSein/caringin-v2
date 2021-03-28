<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use Validator;
use Exception;

use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

class TarifController extends Controller
{
    public function __construct()
    {
        $this->middleware('tarif');
    }

    public function index(){
        return view('utilities.tarif.index',[
            'listrik'  => TarifListrik::first(),
            'airbersih'=> TarifAirBersih::first()
        ]);
    }

    public function keamananipk(Request $request){
        if(request()->ajax()){
            $data = TarifKeamananIpk::orderBy('tarif','asc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="keamananipk" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->tarif.'" fas="keamananipk" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->editColumn('tarif', function ($data) {
                    return number_format($data->tarif);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function kebersihan(Request $request){
        if(request()->ajax()){
            $data = TarifKebersihan::orderBy('tarif','asc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="kebersihan" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->tarif.'" fas="kebersihan" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->editColumn('tarif', function ($data) {
                    return number_format($data->tarif);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function airkotor(Request $request){
        if(request()->ajax()){
            $data = TarifAirKotor::orderBy('tarif','asc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="airkotor" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->tarif.'" fas="airkotor" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->editColumn('tarif', function ($data) {
                    return number_format($data->tarif);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function lain(Request $request){
        if(request()->ajax()){
            $data = TarifLain::orderBy('tarif','asc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if(Session::get('role') == 'master'){
                        $button = '<a type="button" title="Edit" name="edit" id="'.$data->id.'" fas="lain" class="edit"><i class="fas fa-edit" style="color:#4e73df;"></i></a>';
                        $button .= '&nbsp;&nbsp;<a type="button" title="Hapus" name="delete" id="'.$data->id.'" nama="'.$data->tarif.'" fas="lain" class="delete"><i class="fas fa-trash-alt" style="color:#e74a3b;"></i></a>';
                    }
                    else
                        $button = '<span style="color:#4e73df;"><i class="fas fa-ban"></i></span>';
                    return $button;
                })
                ->editColumn('tarif', function ($data) {
                    return number_format($data->tarif);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request){
        $dataset = array();
        $role = '';
        try{
            $keamananipk = $request->checkKeamananIpk;
            $kebersihan = $request->checkKebersihan;
            $airkotor = $request->checkAirKotor;
            $lain = $request->checkLain;

            if(empty($keamananipk) == FALSE){
                if($request->prs_keamanan > 100 || $request->prs_ipk > 100 || $request->prs_keamanan < 0 || $request->prs_ipk < 0){
                    $dataset['status'] = 'error';
                    $dataset['message'] = 'Data Gagal Ditambah';
                    return response()->json(['result' => $dataset]);
                }
                $tarif = explode(',',$request->keamananIpk);
                $tarif = implode('',$tarif);
                $data = [
                    'tarif'        => $tarif,
                    'prs_keamanan' => $request->prs_keamanan,
                    'prs_ipk'      => $request->prs_ipk,
                ];
                TarifKeamananIpk::create($data);
                $role = 'keamananipk';
            }

            if(empty($kebersihan) == FALSE){
                $tarif = explode(',',$request->kebersihan);
                $tarif = implode('',$tarif);
                $data = [
                    'tarif' => $tarif,
                ];
                TarifKebersihan::create($data);
                $role = 'kebersihan';
            }

            if(empty($airkotor) == FALSE){
                $tarif = explode(',',$request->airkotor);
                $tarif = implode('',$tarif);
                $data = [
                    'tarif' => $tarif,
                ];
                TarifAirKotor::create($data);
                $role = 'airkotor';
            }

            if(empty($lain) == FALSE){
                $tarif = explode(',',$request->lain);
                $tarif = implode('',$tarif);
                $data = [
                    'tarif' => $tarif,
                ];
                TarifLain::create($data);
                $role = 'lain';
            }

            $dataset['success'] = 'Data Berhasil Ditambah';
            $dataset['role'] = $role;

            return response()->json(['result' => $dataset]);
        }
        catch(\Exception $e){
            $dataset['success'] = 'Data Gagal Ditambah';
            $dataset['role'] = '';
            return response()->json(['result' => $dataset]);
        }
    }

    public function edit($fasilitas, $id){
        if(request()->ajax())
        {
            $data = '';
            if($fasilitas == 'keamananipk'){
                $data = TarifKeamananIpk::find($id);
                if($data != NULL){
                    $data['keamananipk'] = number_format($data->tarif);
                    $data['prs_keamanan'] = $data->prs_keamanan;
                    $data['prs_ipk']      = $data->prs_ipk;
                }
            }
            if($fasilitas == 'kebersihan'){
                $data = TarifKebersihan::find($id);
                if($data != NULL){
                    $data = number_format($data->tarif);
                }
            }
            if($fasilitas == 'airkotor'){
                $data = TarifAirKotor::find($id);
                if($data != NULL){
                    $data = number_format($data->tarif);
                }
            }
            if($fasilitas == 'lain'){
                $data = TarifLain::find($id);
                if($data != NULL){
                    $data = number_format($data->tarif);
                }
            }
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request){
        if($request->fasilitas == 'listrik'){
            $beban = explode(',',$request->bebanListrik);
            $beban = implode('',$beban);

            $blok1 = explode(',',$request->blok1);
            $blok1 = implode('',$blok1);

            $blok2 = explode(',',$request->blok2);
            $blok2 = implode('',$blok2);
            
            $denda1 = explode(',',$request->denda1);
            $denda1 = implode('',$denda1);

            $pasangListrik = explode(',',$request->pasangListrik);
            $pasangListrik = implode('',$pasangListrik);

            $data = [
                'trf_rekmin'        => $request->rekmin,
                'trf_beban'        => $beban,
                'trf_blok1'        => $blok1,
                'trf_blok2'        => $blok2,
                'trf_standar'      => $request->waktu,
                'trf_bpju'         => $request->bpju,
                'trf_denda'        => $denda1,
                'trf_denda_lebih'  => $request->denda2,
                'trf_ppn'          => $request->ppnListrik,
                'trf_pasang'       => $pasangListrik
            ];      
            
            try{
                TarifListrik::first()->update($data);
                return response()->json(['success' => 'Data Berhasil Disimpan']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Disimpan']);
            }
        }

        if($request->fasilitas == 'air'){
            $beban = explode(',',$request->bebanAir);
            $beban = implode('',$beban);

            $tarif1 = explode(',',$request->tarif1);
            $tarif1 = implode('',$tarif1);

            $tarif2 = explode(',',$request->tarif2);
            $tarif2 = implode('',$tarif2);
            
            $dendaAir = explode(',',$request->dendaAir);
            $dendaAir = implode('',$dendaAir);
            
            $pemeliharaan = explode(',',$request->pemeliharaan);
            $pemeliharaan = implode('',$pemeliharaan);

            $pasangAir = explode(',',$request->pasangAir);
            $pasangAir = implode('',$pasangAir);

            $data = [
                'trf_beban'        => $beban,
                'trf_1'            => $tarif1,
                'trf_2'            => $tarif2,
                'trf_pemeliharaan' => $pemeliharaan,
                'trf_arkot'        => $request->arkot,
                'trf_denda'        => $dendaAir,
                'trf_ppn'          => $request->ppnAir,
                'trf_pasang'       => $pasangAir
            ];      
            
            try{
                TarifAirBersih::first()->update($data);
                return response()->json(['success' => 'Data Berhasil Disimpan']);
            }
            catch(\Exception $e){
                return response()->json(['errors' => 'Data Gagal Disimpan']);
            }

            if($request->fasilitas == 'tarif'){

            }
        }
        if($request->fasilitas == 'tarif'){
            $dataset = array();
            $role = '';
            try{
                $keamananipk = $request->checkKeamananIpk;
                $kebersihan = $request->checkKebersihan;
                $airkotor = $request->checkAirKotor;
                $lain = $request->checkLain;

                if(empty($keamananipk) == FALSE){
                    if($request->prs_keamanan > 100 || $request->prs_ipk > 100 || $request->prs_keamanan < 0 || $request->prs_ipk < 0){
                        $dataset['status'] = 'error';
                        $dataset['message'] = 'Data Gagal Diedit';
                        return response()->json(['result' => $dataset]);
                    }
                    $tarif = explode(',',$request->keamananIpk);
                    $tarif = implode('',$tarif);
                    $data = [
                        'tarif'        => $tarif,
                        'prs_keamanan' => $request->prs_keamanan,
                        'prs_ipk'      => $request->prs_ipk,
                    ];
                    TarifKeamananIpk::whereId($request->hidden_id)->update($data);
                    $role = 'keamananipk';
                }

                if(empty($kebersihan) == FALSE){
                    $tarif = explode(',',$request->kebersihan);
                    $tarif = implode('',$tarif);
                    $data = [
                        'tarif' => $tarif,
                    ];
                    TarifKebersihan::whereId($request->hidden_id)->update($data);
                    $role = 'kebersihan';
                }

                if(empty($airkotor) == FALSE){
                    $tarif = explode(',',$request->airkotor);
                    $tarif = implode('',$tarif);
                    $data = [
                        'tarif' => $tarif,
                    ];
                    TarifAirKotor::whereId($request->hidden_id)->update($data);
                    $role = 'airkotor';
                }

                if(empty($lain) == FALSE){
                    $tarif = explode(',',$request->lain);
                    $tarif = implode('',$tarif);
                    $data = [
                        'tarif' => $tarif,
                    ];
                    TarifLain::whereId($request->hidden_id)->update($data);
                    $role = 'lain';
                }

                $dataset['success'] = 'Data Berhasil Diupdate';
                $dataset['role'] = $role;

                return response()->json(['result' => $dataset]);
            }
            catch(\Exception $e){
                $dataset['errors'] = 'Data Gagal Diupdate';
                $dataset['role'] = '';
                return response()->json(['result' => $dataset]);
            }
        }
    }

    public function destroy($fasilitas, $id){
        $dataset = array();
        $role = '';
        if($fasilitas == 'keamananipk'){
            $data = TarifKeamananIpk::find($id);
            $role = 'keamananipk';
        }
        if($fasilitas == 'kebersihan'){
            $data = TarifKebersihan::find($id);
            $role = 'kebersihan';
        }
        if($fasilitas == 'airkotor'){
            $data = TarifAirKotor::find($id);
            $role = 'airkotor';
        }
        if($fasilitas == 'lain'){
            $data = TarifLain::find($id);
            $role = 'lain';
        }
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
