<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\Dashboard;
use App\Models\TempatUsaha;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\Keuangan;
use App\Models\Pembayaran;
use App\Models\Harian;

use Carbon\Carbon;
use App\Models\IndoDate;
use DataTables;
use Exception;

use App\Models\LevindCrypt;

class KeuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('keuangan');
    }

    public function tagihan(Request $request, $fasilitas){
        $now = date("Y-m-d",strtotime(Carbon::now()));
        $check = date("Y-m-23",strtotime(Carbon::now()));

        if($now < $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime(Carbon::now()));
        }
        else if($now >= $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime("+1 month", $time));
        }

        if($request->periode !== NULL || $request->periode != '')
            Session::put('periodetagihan',$request->periode);
        else
            Session::put('periodetagihan',$periode);

        $fas = Session::put("fasilitas",$fasilitas);

        if(request()->ajax()){
            if(Session::get("fasilitas") != 'tagihan')
                $data = Tagihan::where([['bln_tagihan',Session::get('periodetagihan')],["stt_$fasilitas",'!=',NULL]]);
            else
                $data = Tagihan::where('bln_tagihan',Session::get('periodetagihan'));
            return DataTables::of($data)
            ->editColumn("ttl_$fasilitas", function ($data) {
                $data['fasilitas'] = Session::get("fasilitas");
                if($data->fasilitas == 'listrik')
                    $hasil = $data->ttl_listrik;
                else if($data->fasilitas == 'airbersih')
                    $hasil = $data->ttl_airbersih;
                else if($data->fasilitas == 'keamananipk')
                    $hasil = $data->ttl_keamananipk;
                elseif($data->fasilitas == 'kebersihan')
                    $hasil = $data->ttl_kebersihan;
                else if($data->fasilitas == 'airkotor')
                    $hasil = $data->ttl_airkotor;
                else if($data->fasilitas == 'lain')
                    $hasil = $data->ttl_lain;
                else
                    $hasil = $data->ttl_tagihan;
                $hasil = number_format($hasil);
                return "<span style='color:#172b4d;'>$hasil</span></a>";
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns([
                'show',
                "ttl_$fasilitas",
            ])
            ->make(true);
        }
        return view("keuangan.tagihan.$fasilitas",[
            "periode" => IndoDate::bulan(Session::get("periodetagihan"), " ")
        ]);
    }

    public function tagihanDetails($id){
        if(request()->ajax()){
            $id = LevindCrypt::decryptString($id);
            $data = Tagihan::find($id);
            $data['periode'] = IndoDate::bulan($data->bln_tagihan, " ");
            $data['data_periode'] = $data->bln_tagihan;

            return response()->json(["result" => $data]);
        }
    }

    public function tagihanGenerate(Request $request){
        $data = $request->hidden_data;
        $bulan = $request->tahun_generate."-".$request->bulan_generate;
        if($data == 'listrik'){
            $rekap = Keuangan::rekapListrik($bulan);

            return view("keuangan.tagihan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapListrik($rekap),
                'rincian'=>Keuangan::rincianListrik($bulan),
            ]);
        }

        else if($data == 'airbersih'){
            $rekap = Keuangan::rekapAirBersih($bulan);

            return view("keuangan.tagihan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapAirBersih($rekap),
                'rincian'=>Keuangan::rincianAirBersih($bulan),
            ]);
        }

        else if($data == 'keamananipk'){
            $rekap = Keuangan::rekapKeamananIpk($bulan);

            return view("keuangan.tagihan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapKeamananIpk($rekap),
                'rincian'=>Keuangan::rincianKeamananIpk($bulan),
            ]);
        }

        else if($data == 'kebersihan'){
            $rekap = Keuangan::rekapKebersihan($bulan);

            return view("keuangan.tagihan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapKebersihan($rekap),
                'rincian'=>Keuangan::rincianKebersihan($bulan),
            ]);
        }

        else if($data == 'airkotor'){
            return view("keuangan.tagihan.generate.$data");
        }

        else if($data == 'lain'){
            return view("keuangan.tagihan.generate.$data");
        }

        else if($data == 'tagihan'){
            $rekap = Keuangan::rekapTotal($bulan);

            return view("keuangan.tagihan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapTotal($rekap),
                'rincian'=>Keuangan::rincianTotal($bulan),
            ]);
        }
    }

    public function tunggakan(Request $request, $fasilitas){
        $now = date("Y-m-d",strtotime(Carbon::now()));
        $check = date("Y-m-23",strtotime(Carbon::now()));

        if($now < $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime(Carbon::now()));
        }
        else if($now >= $check){
            $sekarang = date("Y-m", strtotime(Carbon::now()));
            $time     = strtotime($sekarang);
            $periode  = date("Y-m", strtotime("+1 month", $time));
        }

        if($request->periode !== NULL || $request->periode != '')
            Session::put('periodetagihan',$request->periode);
        else
            Session::put('periodetagihan',$periode);

        $fas = Session::put("fasilitas",$fasilitas);

        if(request()->ajax()){
            if(Session::get("fasilitas") != 'tagihan')
                $data = Tagihan::where([['bln_tagihan',Session::get('periodetagihan')],["sel_$fasilitas",'>',0],["stt_$fasilitas",'!=',NULL]]);
            else
                $data = Tagihan::where([['bln_tagihan',Session::get('periodetagihan')],["sel_$fasilitas",'>',0]]);
            return DataTables::of($data)
            ->editColumn("sel_$fasilitas", function ($data) {
                $data['fasilitas'] = Session::get("fasilitas");
                if($data->fasilitas == 'listrik')
                    $hasil = $data->sel_listrik;
                else if($data->fasilitas == 'airbersih')
                    $hasil = $data->sel_airbersih;
                else if($data->fasilitas == 'keamananipk')
                    $hasil = $data->sel_keamananipk;
                elseif($data->fasilitas == 'kebersihan')
                    $hasil = $data->sel_kebersihan;
                else if($data->fasilitas == 'airkotor')
                    $hasil = $data->sel_airkotor;
                else if($data->fasilitas == 'lain')
                    $hasil = $data->sel_lain;
                else
                    $hasil = $data->sel_tagihan;
                $hasil = number_format($hasil);
                return "<span style='color:#172b4d;'>$hasil</span></a>";
            })
            ->addColumn('show', function($data){
                $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                return $button;
            })
            ->rawColumns([
                'show',
                "sel_$fasilitas",
            ])
            ->make(true);
        }
        return view("keuangan.tunggakan.$fasilitas",[
            "periode" => IndoDate::bulan(Session::get("periodetagihan"), " ")
        ]);
    }

    public function tunggakanGenerate(Request $request){
        $data = $request->hidden_data;
        $bulan = $request->tahun_generate."-".$request->bulan_generate;
        if($data == 'listrik'){
            $rekap = Keuangan::tunggakanListrik($bulan);

            return view("keuangan.tunggakan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlTunggakanListrik($rekap),
                'rincian'=>Keuangan::rincianTunggakanListrik($bulan),
            ]);
        }

        else if($data == 'airbersih'){
            $rekap = Keuangan::tunggakanAirBersih($bulan);

            return view("keuangan.tunggakan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlTunggakanAirBersih($rekap),
                'rincian'=>Keuangan::rincianTunggakanAirBersih($bulan),
            ]);
        }

        else if($data == 'keamananipk'){
            $rekap = Keuangan::tunggakanKeamananIpk($bulan);

            return view("keuangan.tunggakan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlTunggakanKeamananIpk($rekap),
                'rincian'=>Keuangan::rincianTunggakanKeamananIpk($bulan),
            ]);
        }

        else if($data == 'kebersihan'){
            $rekap = Keuangan::tunggakanKebersihan($bulan);

            return view("keuangan.tunggakan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlTunggakanKebersihan($rekap),
                'rincian'=>Keuangan::rincianTunggakanKebersihan($bulan),
            ]);
        }

        else if($data == 'airkotor'){
            return view("keuangan.tunggakan.generate.$data");
        }

        else if($data == 'lain'){
            return view("keuangan.tunggakan.generate.$data");
        }

        else if($data == 'tagihan'){
            $rekap = Keuangan::tunggakanTotal($bulan);

            return view("keuangan.tunggakan.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlTunggakanTotal($rekap),
                'rincian'=>Keuangan::rincianTunggakanTotal($bulan),
            ]);
        }
    }

    public function pendapatan($data){
        if(request()->ajax())
        {
            if($data == 'harian'){
                $data = Pembayaran::orderBy('tgl_bayar','desc');
                return DataTables::of($data)
                ->editColumn('realisasi', function ($data) {
                    return number_format($data->realisasi);
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->id).'" nama="'.$data->kd_kontrol.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }

            else if($data == 'bulanan'){
                $data = Pembayaran::select('bln_bayar')->groupBy('bln_bayar')->orderBy('bln_bayar','desc');
                return DataTables::of($data)
                ->addColumn('realisasi', function($data){
                    $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->bln_bayar).'" nama="'.$data->bln_bayar.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }

            else if($data == 'tahunan'){
                $data = Pembayaran::select('thn_bayar')->groupBy('thn_bayar')->orderBy('thn_bayar','desc');
                return DataTables::of($data)
                ->addColumn('realisasi', function($data){
                    $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->thn_bayar).'" nama="'.$data->thn_bayar.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }
        }
    
        return view("keuangan.pendapatan.$data");
    }

    public function pendapatanGenerate(Request $request){
        $data = $request->hidden_data;
        if($data == 'harian'){
            $fasilitas = $request->fasilitas;
            $tanggal   = $request->tanggal_generate;
            $cetak   = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

            if($fasilitas == 'listrik'){
                $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_listrik','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'airbersih'){
                $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_airbersih','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'keamananipk'){
                $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_keamananipk','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'kebersihan'){
                $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_kebersihan','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'airkotor'){
                $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_airkotor','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'lain'){
                $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_lain','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'tagihan'){
                $dataset = Pembayaran::where('tgl_bayar',$tanggal)->orderBy('kd_kontrol','asc')->get();
            }

            return view("keuangan.pendapatan.generate.harian.$fasilitas",[
                'dataset' => $dataset, 
                'tanggal' => IndoDate::tanggal($tanggal, " "),
                'cetak'   => $cetak,
            ]);
        }
        else if($data == 'bulanan'){
            $fasilitas = $request->fasilitas;
            $periode   = $request->tahun_generate."-".$request->bulan_generate;
            $cetak     = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

            if($fasilitas == 'listrik'){
                $dataset = Pembayaran::where([['bln_bayar',$periode],['byr_listrik','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'airbersih'){
                $dataset = Pembayaran::where([['bln_bayar',$periode],['byr_airbersih','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'keamananipk'){
                $dataset = Pembayaran::where([['bln_bayar',$periode],['byr_keamananipk','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'kebersihan'){
                $dataset = Pembayaran::where([['bln_bayar',$periode],['byr_kebersihan','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'airkotor'){
                $dataset = Pembayaran::where([['bln_bayar',$periode],['byr_airkotor','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'lain'){
                $dataset = Pembayaran::where([['bln_bayar',$periode],['byr_lain','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'tagihan'){
                $dataset = Pembayaran::where('bln_bayar',$periode)->orderBy('kd_kontrol','asc')->get();
            }
            
            return view("keuangan.pendapatan.generate.bulanan.$fasilitas",[
                'dataset' => $dataset,
                'bulan'   => IndoDate::bulan($periode, " "),
                'cetak'   => $cetak,
            ]);
        }
        else if($data == 'tahunan'){
            $fasilitas = $request->fasilitas;
            $periode   = $request->tahun_generate;
            $cetak     = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

            if($fasilitas == 'listrik'){
                $dataset = Pembayaran::where([['thn_bayar',$periode],['byr_listrik','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'airbersih'){
                $dataset = Pembayaran::where([['thn_bayar',$periode],['byr_airbersih','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'keamananipk'){
                $dataset = Pembayaran::where([['thn_bayar',$periode],['byr_keamananipk','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'kebersihan'){
                $dataset = Pembayaran::where([['thn_bayar',$periode],['byr_kebersihan','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'airkotor'){
                $dataset = Pembayaran::where([['thn_bayar',$periode],['byr_airkotor','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'lain'){
                $dataset = Pembayaran::where([['thn_bayar',$periode],['byr_lain','>',0]])->orderBy('kd_kontrol','asc')->get();
            }
            else if($fasilitas == 'tagihan'){
                $dataset = Pembayaran::where('thn_bayar',$periode)->orderBy('kd_kontrol','asc')->get();
            }
            
            return view("keuangan.pendapatan.generate.tahunan.$fasilitas",[
                'dataset' => $dataset,
                'tahun'   => $periode,
                'cetak'   => $cetak,
            ]);
        }
    }

    public function rekap($data){
        if(request()->ajax())
        {
            if($data == 'sisa'){
                $data = Tagihan::select('blok')->groupBy('blok')->orderBy('blok','asc');
                return DataTables::of($data)
                ->addColumn('tagihan', function ($data) {
                    $tagihan = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$data->blok]])
                    ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->blok).'" nama="'.$data->blok.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }

            else if($data == 'akhir'){
                $data = Pembayaran::select('bln_bayar')->groupBy('bln_bayar')->orderBy('bln_bayar','desc');
                return DataTables::of($data)
                ->addColumn('realisasi', function($data){
                    $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                    ->select(DB::raw('SUM(realisasi) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('show', function($data){
                    $button = '<button title="Show Details" name="show" id="'.LevindCrypt::encryptString($data->bln_bayar).'" nama="'.$data->bln_bayar.'" class="details btn btn-sm btn-primary">Show</button>';
                    return $button;
                })
                ->rawColumns([
                    'show'
                ])
                ->make(true);
            }
        }
    
        return view("keuangan.rekap.$data");
    }

    public function rekapGenerate(Request $request){
        $data = $request->hidden_data;
        if($data == 'sisa'){$bulan   = IndoDate::bulan(date('Y-m',strtotime(Carbon::now())),' ');
        
            if($request->sisatagihan == 'all'){
                $dataset = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]])->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
            }
            else{
                $dataset = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]])->whereIn('blok',$request->sebagian)->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
            }
            
            $rekap       = array();
            $rek         = 0;
            $listrik     = 0;
            $denlistrik  = 0;
            $airbersih   = 0;
            $denairbersih= 0;
            $keamananipk = 0;
            $kebersihan  = 0;
            $airkotor    = 0;
            $lain        = 0;
            $jumlah      = 0;
            $diskon      = 0;
    
            $rin          = array();
    
            $i = 0;
            $j = 0;
    
            foreach($dataset as $d){
                $rekap[$i]['blok'] = $d->blok;
                $rekap[$i]['rek']  = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$d->blok]])->count();
                $setor = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$d->blok]])
                ->select(
                    DB::raw('SUM(sel_listrik)      as listrik'),
                    DB::raw('SUM(den_listrik)      as denlistrik'),
                    DB::raw('SUM(sel_airbersih)    as airbersih'),
                    DB::raw('SUM(den_airbersih)    as denairbersih'),
                    DB::raw('SUM(sel_keamananipk)  as keamananipk'),
                    DB::raw('SUM(sel_kebersihan)   as kebersihan'),
                    DB::raw('SUM(sel_airkotor)     as airkotor'),
                    DB::raw('SUM(sel_lain)         as lain'),
                    DB::raw('SUM(sel_tagihan)      as jumlah'),
                    DB::raw('SUM(dis_tagihan)      as diskon'))
                ->get();
                
                $rekap[$i]['listrik']     = $setor[0]->listrik - $setor[0]->denlistrik;
                $rekap[$i]['denlistrik']  = $setor[0]->denlistrik;
                $rekap[$i]['airbersih']   = $setor[0]->airbersih - $setor[0]->denairbersih;
                $rekap[$i]['denairbersih']= $setor[0]->denairbersih;
                $rekap[$i]['keamananipk'] = $setor[0]->keamananipk;
                $rekap[$i]['kebersihan']  = $setor[0]->kebersihan;
                $rekap[$i]['airkotor']    = $setor[0]->airkotor;
                $rekap[$i]['lain']        = $setor[0]->lain;
                $rekap[$i]['diskon']      = $setor[0]->diskon;
                $rekap[$i]['jumlah']      = $setor[0]->jumlah;
                $rek         = $rek         + $rekap[$i]['rek'];
                $listrik     = $listrik     + $rekap[$i]['listrik'];
                $denlistrik  = $denlistrik  + $rekap[$i]['denlistrik'];
                $airbersih   = $airbersih   + $rekap[$i]['airbersih'];
                $denairbersih= $denairbersih+ $rekap[$i]['denairbersih'];
                $keamananipk = $keamananipk + $rekap[$i]['keamananipk'];
                $kebersihan  = $kebersihan  + $rekap[$i]['kebersihan'];
                $airkotor    = $airkotor    + $rekap[$i]['airkotor'];
                $lain        = $lain        + $rekap[$i]['lain'];
                $diskon      = $diskon      + $rekap[$i]['diskon'];
                $jumlah      = $jumlah      + $rekap[$i]['jumlah'];
    
                $rincian = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0],['blok',$d->blok]])->orderBy('kd_kontrol','asc')->get();
                foreach($rincian as $r){
                    $rin[$j]['blok']  = $r->blok;
                    $rin[$j]['kode']  = $r->kd_kontrol;
                    $rin[$j]['pengguna']  = $r->nama;
                    $rin[$j]['listrik']  = $r->sel_listrik - $r->den_listrik;
                    $rin[$j]['denlistrik']  = $r->den_listrik;
                    $rin[$j]['airbersih']  = $r->sel_airbersih - $r->den_airbersih;
                    $rin[$j]['denairbersih']  = $r->den_airbersih;
                    $rin[$j]['keamananipk']  = $r->sel_keamananipk;
                    $rin[$j]['kebersihan']  = $r->sel_kebersihan;
                    $rin[$j]['airkotor']  = $r->sel_airkotor;
                    $rin[$j]['lain']  = $r->sel_lain;
                    $rin[$j]['jumlah']  = $r->sel_tagihan;
                    $rin[$j]['diskon']  = $r->dis_tagihan;
    
                    $tempat = TempatUsaha::where('kd_kontrol',$r->kd_kontrol)->first();
                    if($tempat != NULL){
                        $rin[$j]['lokasi'] = $tempat->lok_tempat;
                    }
                    else{
                        $rin[$j]['lokasi'] = '';
                    }
    
                    $j++;
                }
    
                $i++;
            }
            $t_rekap['rek']          = $rek;
            $t_rekap['listrik']      = $listrik;
            $t_rekap['denlistrik']   = $denlistrik;
            $t_rekap['airbersih']    = $airbersih;
            $t_rekap['denairbersih'] = $denairbersih;
            $t_rekap['keamananipk']  = $keamananipk;
            $t_rekap['kebersihan']   = $kebersihan;
            $t_rekap['airkotor']     = $airkotor;
            $t_rekap['lain']         = $lain;
            $t_rekap['diskon']       = $diskon;
            $t_rekap['jumlah']       = $jumlah;
    
            return view("keuangan.rekap.generate.$data",[
                'dataset'   => $dataset,
                'bulan'     => $bulan,
                'rekap'     => $rekap,
                't_rekap'   => $t_rekap,
                'rincian'   => $rin
            ]);
        }
        else if($data == 'akhir'){
            $cetak   = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));
            $periode = $request->tahunselesai."-".$request->bulanselesai;
            $dataku = Pembayaran::where('bln_bayar',$periode)->get();
            $listrik = 0;
            $denlistrik = 0;
            $airbersih = 0;
            $denairbersih = 0;
            $keamananipk = 0;
            $kebersihan = 0;
            $airkotor = 0;
            $lain = 0;
            foreach($dataku as $d){
                $listrik = $listrik + $d->byr_listrik;
                $denlistrik = $denlistrik + $d->byr_denlistrik;
                $airbersih = $airbersih + $d->byr_airbersih;
                $denairbersih = $denairbersih + $d->byr_denairbersih;
                $keamananipk = $keamananipk + $d->byr_keamananipk;
                $kebersihan = $kebersihan + $d->byr_kebersihan;
                $airkotor = $airkotor + $d->byr_airkotor;
                $lain = $lain + $d->byr_lain;
            }
            $dataset = array();
            $dataset[0]['items'] = 'Listrik';
            $dataset[0]['total'] = $listrik - $denlistrik;
            $dataset[0]['denda'] = $denlistrik;
            $dataset[1]['items'] = 'Air Bersih';
            $dataset[1]['total'] = $airbersih - $denairbersih;
            $dataset[1]['denda'] = $denairbersih;
            $dataset[2]['items'] = 'Keamanan IPK';
            $dataset[2]['total'] = $keamananipk;
            $dataset[2]['denda'] = NULL;
            $dataset[3]['items'] = 'Kebersihan';
            $dataset[3]['total'] = $kebersihan;
            $dataset[3]['denda'] = NULL;
            $dataset[4]['items'] = 'Air Kotor';
            $dataset[4]['total'] = $airkotor;
            $dataset[4]['denda'] = NULL;
            $dataset[5]['items'] = 'Lain - Lain';
            $dataset[5]['total'] = $lain;
            $dataset[5]['denda'] = NULL;
            $bulan = IndoDate::bulan($periode," ");

            $rincian = Pembayaran::where('bln_bayar',$periode)->orderBy('tgl_bayar','asc')->orderBy('kd_kontrol','asc')->get();

            return view("keuangan.rekap.generate.$data",[
                'dataset' => $dataset,
                'rincian' => $rincian,
                'bulan'   => $bulan,
                'cetak'   => $cetak
            ]);
        }
    }
}
