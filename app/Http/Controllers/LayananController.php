<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;
use Validator;
use Exception;

use App\Models\Pedagang;
use App\Models\TempatUsaha;

use App\Models\AlatListrik;
use App\Models\AlatAir;
use App\Models\TarifListrik;
use App\Models\TarifAirBersih;
use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

use App\Models\PasangAlat;
use App\Models\Sinkronisasi;
use App\Models\Tagihan;
use App\Models\Bongkaran;
use App\Models\Penghapusan;

use App\Models\IndoDate;
use Carbon\Carbon;
use App\Models\Carbonet;

class LayananController extends Controller
{
    public function __construct()
    {
        $this->middleware('layanan');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layanan.registrasi');
    }

    public function tempat(Request $request, $id){
        if($request->ajax()){
            try{
                $data = TempatUsaha::find($id);
                return response()->json(['result' => $data]);
        
            }
            catch(\Exception $e){
                return response()->json(['errors' => $e]);
            }
        }
    }

    public function diskon(Request $request){
        $fasilitas = $request->fasilitasdiskon;
        
        $cetak = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

        $dataset = TempatUsaha::whereIn('id',$request->kontroldiskon)->orderBy('kd_kontrol','asc')->get();

        $i = 0;
        $data = array();
        foreach($dataset as $d){
            $pengguna = Pedagang::find($d->id_pengguna);
            $data[$i]['nama'] = $pengguna->nama;
            $data[$i]['kontrol'] = $d->kd_kontrol;
            $data[$i]['blok'] = $d->blok;
            $data[$i]['los'] = $d->no_alamat;
            $data[$i]['unit'] = $d->jml_alamat;
            if($fasilitas == 'keamananipk'){
                $tarif = TarifKeamananIpk::find($d->trf_keamananipk);
                if($tarif == NULL)
                    $data[$i]['tarif'] = 0;
                else
                    $data[$i]['tarif'] = $tarif->tarif;
            }
            else if($fasilitas == 'kebersihan'){
                $tarif = TarifKebersihan::find($d->trf_kebersihan);
                if($tarif == NULL)
                    $data[$i]['tarif'] = 0;
                else
                    $data[$i]['tarif'] = $tarif->tarif;
            }

            $i++; 
        }

        if($fasilitas == 'listrik'){
            return view('layanan.diskon.listrik',[
                'dataset' => $data,
                'cetak'   => $cetak
            ]);
        }
        elseif($fasilitas == 'airbersih'){
            return view('layanan.diskon.airbersih',[
                'dataset' => $data,
                'cetak'   => $cetak
            ]);
        }
        elseif($fasilitas == 'keamananipk'){
            return view('layanan.diskon.keamananipk',[
                'dataset' => $data,
                'cetak'   => $cetak
            ]);
        }
        elseif($fasilitas == 'kebersihan'){
            return view('layanan.diskon.kebersihan',[
                'dataset' => $data,
                'cetak'   => $cetak
            ]);
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
        //
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
