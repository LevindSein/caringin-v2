<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\TempatUsaha;
use App\Models\Tagihan;
use App\Models\Pedagang;
use App\Models\IndoDate;
use App\Models\Terbilang;
use App\Models\Carbonet;
use App\Models\Sinkronisasi;
use App\Models\AlatAir;
use App\Models\AlatListrik;
use Carbon\Carbon;

use App\Models\LevindCrypt;

use DataTables;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BongkaranExport;

class LayananController extends Controller
{
    public function __construct()
    {
        $this->middleware('layanan');
    }

    public function nasabahIndex(){
        return view('layanan.nasabah.index');
    }

    public function alatMeterIndex(){
        return view('layanan.alatmeter.index');
    }

    public function alatMeterGanti(){
        return view('layanan.alatmeter.ganti');
    }

    public function alatMeterBongkar(){
        return view('layanan.alatmeter.bongkar');
    }

    public function alatMeterRiwayat(){
        if(request()->ajax()){

        }
        
        return view('layanan.alatmeter.riwayat');
    }

    public function bongkaranIndex(){
        if(request()->ajax()){
            $data = TempatUsaha::where('stt_bongkar','>',1)->orderBy('stt_bongkar','desc');
            return DataTables::of($data)
                ->addColumn('action', function($data){
                        $button = '<button data-toggle="tooltip" data-original-title="Unduh Surat" name="surat" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="surat btn btn-sm btn-info">Surat</button>';
                        $button .= '<button data-toggle="tooltip" data-original-title="Selesai" name="selesai" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="selesai btn btn-sm btn-success">Selesai</button>';
                        return $button;
                })
                ->editColumn('stt_bongkar', function($data){
                    if($data->stt_bongkar < 4)
                        $button = '<span style="color:#e74a3b;">'.$data->stt_bongkar.' Bulan</span>';
                    else
                        $button = '<span style="color:#e74a3b;">&GreaterEqual; '.$data->stt_bongkar.' Bulan</span>';
                    return $button;
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns(['action','stt_bongkar','show'])
                ->make(true);
        }
        return view('layanan.bongkaran.index');
    }

    public function bongkaranSurat(Request $request){
        $id = LevindCrypt::decryptString($request->surat_id);
        $kontrol = TempatUsaha::find($id)->kd_kontrol;
        $dataset = TempatUsaha::find($id);
        $surat = $request->surat;
        $dataset['cetak'] = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));
        
        if($surat == 'peringatan'){
            $surat = "Peringatan";
            $dataset['pengguna'] = Pedagang::find($dataset->id_pengguna)->nama;
            $dataset['admin'] = Session::get('username');
            $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1]])->get();
            $total = 0;
            foreach($tagihan as $t){
                $t['bulan'] = IndoDate::bulan($t->bln_tagihan, ' ');
                $total = $total + $t->sel_tagihan;
            }
            $terbilang = ucfirst(Terbilang::convert($total));

            if(date('d',strtotime(Carbon::now())) <= 15)
                $expired = IndoDate::tanggal(date('Y-m-15',strtotime(Carbon::now())), ' ');
            else
                $expired = IndoDate::tanggal(date("Y-m-15", strtotime("+1 month", strtotime(Carbon::now()))), ' ');
            
            $pdf = PDF::loadview('layanan.bongkaran.surat.peringatan',['data' => $dataset, 'tagihan' => $tagihan, 'total' => $total, 'terbilang' => $terbilang, 'expired' => $expired]);
            return $pdf->download("surat-peringatan $kontrol.pdf");
        }
        else if($surat == 'pembongkaran'){
            $surat = "Pembongkaran";
            $dataset['admin'] = Session::get('username');
            $pengguna = Pedagang::find($dataset->id_pengguna);

            $date = date('Y-m-01', strtotime(Carbon::now()));
            $month = date('m', strtotime(Carbon::now()));
            $year = date('Y', strtotime(Carbon::now()));
            $sync = Sinkronisasi::where('sinkron',$date)->first();
            $sync->surat += 1;

            $dataset['air'] = 0;
            $dataset['listrik'] = 0;

            if($dataset->id_meteran_air != NULL){
                $dataset['data_air'] = AlatAir::find($dataset->id_meteran_air);
                $dataset['air'] = 1;
                $dataset['nomor_air'] = sprintf("%'03d", $sync->surat) . '/PBMA/U.ME/' . Carbonet::roman($month) . "/$year";
            }
            if($dataset->id_meteran_listrik != NULL){
                $dataset['data_listrik'] = AlatListrik::find($dataset->id_meteran_listrik);
                $dataset['listrik'] = 1;
                $dataset['nomor_listrik'] = sprintf("%'03d", $sync->surat) . '/PBML/U.ME/' . Carbonet::roman($month) . "/$year";
            }

            $sync->save();

            $pdf = PDF::loadview('layanan.bongkaran.surat.pembongkaran',['data' => $dataset, 'pengguna' => $pengguna]);
            return $pdf->download("surat-perintah-bongkar $kontrol.pdf");
        }
        else{
            $surat = "Berita Acara";
            $dataset['pengguna'] = Pedagang::find($dataset->id_pengguna)->nama;
            
            $date = date('Y-m-01', strtotime(Carbon::now()));
            $month = date('m', strtotime(Carbon::now()));
            $year = date('Y', strtotime(Carbon::now()));
            $sync = Sinkronisasi::where('sinkron',$date)->first();
            $sync->surat += 1;
            $nomorSurat = sprintf("%'03d", $sync->surat) . '/BA/U.ME/' . Carbonet::roman($month) . "/$year";
            $sync->save();

            $pdf = PDF::loadview('layanan.bongkaran.surat.berita-acara',['data' => $dataset, 'nomor' => $nomorSurat]);
            return $pdf->download("surat-berita-acara $kontrol.pdf");
        }
    }

    public function bongkaranSelesai($id){
        try{
            $id = LevindCrypt::decryptString($id);
            $kontrol = TempatUsaha::find($id)->kd_kontrol;
            
            // $tempat = TempatUsaha::find($id);
            // $tempat->stt_bongkar = 0;
            // $tempat->save();

            // $tagihan = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1]])->get();
            // foreach($tagihan as $t){
            //     $t->delete();
            // }

            return response()->json(['success' => 'Bongkaran diselesaikan.']);
        }
        catch(\Exception $e){
            return response()->json(['errors' => 'Bongkaran gagal diproses.']);
        }
    }

    public function bongkaranShow($id){
        $id = LevindCrypt::decryptString($id);
        $kontrol = TempatUsaha::find($id)->kd_kontrol;

        $dataset = Tagihan::where([['kd_kontrol',$kontrol],['stt_lunas',0],['stt_publish',1]])->get();
        
        foreach($dataset as $d){
            $d['bulan'] = IndoDate::bulanS($d->bln_tagihan, ' ');
        }

        return response()->json(['result' => $dataset]);
    }

    public function bongkaranGenerate(){
        return Excel::download(new BongkaranExport, 'data_bongkaran_BP3C.xlsx');
    }
}
