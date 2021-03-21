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

class KeuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('keuangan');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::now();
        $today = strtotime($today);
        $tahun = date("Y", $today);
        
        $rincian = Dashboard::rincian($tahun);
        $pendapatan = Dashboard::pendapatan($tahun);
        $akumulasi = Dashboard::akumulasi($tahun);

        return view('keuangan.index',[
            'reaAirBersih'=>Dashboard::reaAirBersih($tahun),
            'reaListrik'=>Dashboard::reaListrik($tahun),
            'reaKeamananIpk'=>Dashboard::reaKeamananIpk($tahun),
            'reaKebersihan'=>Dashboard::reaKebersihan($tahun),
            'selAirBersih'=>Dashboard::selAirBersih($tahun),
            'selListrik'=>Dashboard::selListrik($tahun),
            'selKeamananIpk'=>Dashboard::selKeamananIpk($tahun),
            'selKebersihan'=>Dashboard::selKebersihan($tahun),
            'pengguna'=>TempatUsaha::count(),
            'penggunaAktif'=>TempatUsaha::where('stt_tempat',1)->count(),
            'penggunaNonAktif'=>TempatUsaha::where('stt_tempat',2)->count(),
            'penggunaAirBersih'=>TempatUsaha::where('trf_airbersih',1)->count(),
            'penggunaListrik'=>TempatUsaha::where('trf_listrik',1)->count(),
            'penggunaKeamananIpk'=>TempatUsaha::whereNotNull('trf_keamananipk')->count(),
            'penggunaKebersihan'=>TempatUsaha::whereNotNull('trf_kebersihan')->count(),
            'listrikJan'=>$rincian[0][0],
            'listrikFeb'=>$rincian[0][1],
            'listrikMar'=>$rincian[0][2],
            'listrikApr'=>$rincian[0][3],
            'listrikMei'=>$rincian[0][4],
            'listrikJun'=>$rincian[0][5],
            'listrikJul'=>$rincian[0][6],
            'listrikAgu'=>$rincian[0][7],
            'listrikSep'=>$rincian[0][8],
            'listrikOkt'=>$rincian[0][9],
            'listrikNov'=>$rincian[0][10],
            'listrikDes'=>$rincian[0][11],
            'airJan'=>$rincian[1][0],
            'airFeb'=>$rincian[1][1],
            'airMar'=>$rincian[1][2],
            'airApr'=>$rincian[1][3],
            'airMei'=>$rincian[1][4],
            'airJun'=>$rincian[1][5],
            'airJul'=>$rincian[1][6],
            'airAgu'=>$rincian[1][7],
            'airSep'=>$rincian[1][8],
            'airOkt'=>$rincian[1][9],
            'airNov'=>$rincian[1][10],
            'airDes'=>$rincian[1][11],
            'keamananipkJan'=>$rincian[2][0],
            'keamananipkFeb'=>$rincian[2][1],
            'keamananipkMar'=>$rincian[2][2],
            'keamananipkApr'=>$rincian[2][3],
            'keamananipkMei'=>$rincian[2][4],
            'keamananipkJun'=>$rincian[2][5],
            'keamananipkJul'=>$rincian[2][6],
            'keamananipkAgu'=>$rincian[2][7],
            'keamananipkSep'=>$rincian[2][8],
            'keamananipkOkt'=>$rincian[2][9],
            'keamananipkNov'=>$rincian[2][10],
            'keamananipkDes'=>$rincian[2][11],
            'kebersihanJan'=>$rincian[3][0],
            'kebersihanFeb'=>$rincian[3][1],
            'kebersihanMar'=>$rincian[3][2],
            'kebersihanApr'=>$rincian[3][3],
            'kebersihanMei'=>$rincian[3][4],
            'kebersihanJun'=>$rincian[3][5],
            'kebersihanJul'=>$rincian[3][6],
            'kebersihanAgu'=>$rincian[3][7],
            'kebersihanSep'=>$rincian[3][8],
            'kebersihanOkt'=>$rincian[3][9],
            'kebersihanNov'=>$rincian[3][10],
            'kebersihanDes'=>$rincian[3][11],
            'tagihanJan'=>$pendapatan[0][0],
            'tagihanFeb'=>$pendapatan[0][1],
            'tagihanMar'=>$pendapatan[0][2],
            'tagihanApr'=>$pendapatan[0][3],
            'tagihanMei'=>$pendapatan[0][4],
            'tagihanJun'=>$pendapatan[0][5],
            'tagihanJul'=>$pendapatan[0][6],
            'tagihanAgu'=>$pendapatan[0][7],
            'tagihanSep'=>$pendapatan[0][8],
            'tagihanOkt'=>$pendapatan[0][9],
            'tagihanNov'=>$pendapatan[0][10],
            'tagihanDes'=>$pendapatan[0][11],
            'realisasiJan'=>$pendapatan[1][0],
            'realisasiFeb'=>$pendapatan[1][1],
            'realisasiMar'=>$pendapatan[1][2],
            'realisasiApr'=>$pendapatan[1][3],
            'realisasiMei'=>$pendapatan[1][4],
            'realisasiJun'=>$pendapatan[1][5],
            'realisasiJul'=>$pendapatan[1][6],
            'realisasiAgu'=>$pendapatan[1][7],
            'realisasiSep'=>$pendapatan[1][8],
            'realisasiOkt'=>$pendapatan[1][9],
            'realisasiNov'=>$pendapatan[1][10],
            'realisasiDes'=>$pendapatan[1][11],
            'selisihJan'=>$pendapatan[2][0],
            'selisihFeb'=>$pendapatan[2][1],
            'selisihMar'=>$pendapatan[2][2],
            'selisihApr'=>$pendapatan[2][3],
            'selisihMei'=>$pendapatan[2][4],
            'selisihJun'=>$pendapatan[2][5],
            'selisihJul'=>$pendapatan[2][6],
            'selisihAgu'=>$pendapatan[2][7],
            'selisihSep'=>$pendapatan[2][8],
            'selisihOkt'=>$pendapatan[2][9],
            'selisihNov'=>$pendapatan[2][10],
            'selisihDes'=>$pendapatan[2][11],
            'tagihanJanAku'=>$akumulasi[0][0],
            'tagihanFebAku'=>$akumulasi[0][1],
            'tagihanMarAku'=>$akumulasi[0][2],
            'tagihanAprAku'=>$akumulasi[0][3],
            'tagihanMeiAku'=>$akumulasi[0][4],
            'tagihanJunAku'=>$akumulasi[0][5],
            'tagihanJulAku'=>$akumulasi[0][6],
            'tagihanAguAku'=>$akumulasi[0][7],
            'tagihanSepAku'=>$akumulasi[0][8],
            'tagihanOktAku'=>$akumulasi[0][9],
            'tagihanNovAku'=>$akumulasi[0][10],
            'tagihanDesAku'=>$akumulasi[0][11],
            'realisasiJanAku'=>$akumulasi[1][0],
            'realisasiFebAku'=>$akumulasi[1][1],
            'realisasiMarAku'=>$akumulasi[1][2],
            'realisasiAprAku'=>$akumulasi[1][3],
            'realisasiMeiAku'=>$akumulasi[1][4],
            'realisasiJunAku'=>$akumulasi[1][5],
            'realisasiJulAku'=>$akumulasi[1][6],
            'realisasiAguAku'=>$akumulasi[1][7],
            'realisasiSepAku'=>$akumulasi[1][8],
            'realisasiOktAku'=>$akumulasi[1][9],
            'realisasiNovAku'=>$akumulasi[1][10],
            'realisasiDesAku'=>$akumulasi[1][11],
            'selisihJanAku'=>$akumulasi[2][0],
            'selisihFebAku'=>$akumulasi[2][1],
            'selisihMarAku'=>$akumulasi[2][2],
            'selisihAprAku'=>$akumulasi[2][3],
            'selisihMeiAku'=>$akumulasi[2][4],
            'selisihJunAku'=>$akumulasi[2][5],
            'selisihJulAku'=>$akumulasi[2][6],
            'selisihAguAku'=>$akumulasi[2][7],
            'selisihSepAku'=>$akumulasi[2][8],
            'selisihOktAku'=>$akumulasi[2][9],
            'selisihNovAku'=>$akumulasi[2][10],
            'selisihDesAku'=>$akumulasi[2][11],
            'thn'=>$tahun,
        ]);
    }

    public function lapTagihan(Request $request, $fasilitas){
        $time = strtotime(Carbon::now());
        $bulan = IndoDate::bulan(date("Y-m", $time)," ");
        $dataTahun = Tagihan::select('thn_tagihan')
        ->groupBy('thn_tagihan')
        ->get();

        Session::put('fas', $fasilitas);

        if($request->ajax()){
            $fas = Session::get('fas');
            $data = Tagihan::select('kd_kontrol')
            ->groupBy('kd_kontrol')
            ->orderBy('kd_kontrol','asc')
            ->where([['stt_lunas',0],['stt_publish',1],["sel_$fas",'>',0]]);
            
            return DataTables::of($data)
            ->addColumn('pedagang', function($data){
                $pengguna = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('id_pengguna')->first();
                if($pengguna != NULL){
                    return User::find($pengguna->id_pengguna)->nama;
                }
                else{
                    return '-';
                }
            })
            ->addColumn('ket', function($data){
                $lokasi = TempatUsaha::where('kd_kontrol',$data->kd_kontrol)->select('lok_tempat')->first();
                if($lokasi != NULL){
                    return $lokasi->lok_tempat;
                }
                else{
                    return '';
                }
            })
            ->addColumn('tagihan', function($data){
                $fas = Session::get('fas');
                $tagihan = Tagihan::where([['kd_kontrol',$data->kd_kontrol],['stt_lunas',0],['stt_publish',1]])
                ->select(DB::raw("SUM(sel_$fas) as tagihan"))->get();
                if($tagihan != NULL){
                    $hasil = number_format($tagihan[0]->tagihan);
                    return '<a href="javascript:void(0)" class="totaltagihan" id="'.$data->kd_kontrol.'"><span style="color:#000000;">'.$hasil.'</span></a>';
                }
                else{
                    return 0;
                }
            })
            ->rawColumns([
                'tagihan',
            ])
            ->make(true);
        }

        return view("keuangan.laporan.pemakaian.$fasilitas",[
            'bulan' => $bulan,
            'dataTahun'=>$dataTahun,
        ]);
    }

    public function lapGenerateTagihan(Request $request, $data){
        $bulan = $request->tahun."-".$request->bulan;

        if($data == 'listrik'){
            $rekap = Keuangan::rekapListrik($bulan);

            return view("keuangan.laporan.pemakaian.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapListrik($rekap),
                'rincian'=>Keuangan::rincianListrik($bulan),
            ]);
        }

        if($data == 'airbersih'){
            $rekap = Keuangan::rekapAirBersih($bulan);

            return view("keuangan.laporan.pemakaian.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapAirBersih($rekap),
                'rincian'=>Keuangan::rincianAirBersih($bulan),
            ]);
        }

        if($data == 'keamananipk'){
            $rekap = Keuangan::rekapKeamananIpk($bulan);

            return view("keuangan.laporan.pemakaian.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapKeamananIpk($rekap),
                'rincian'=>Keuangan::rincianKeamananIpk($bulan),
            ]);
        }

        if($data == 'kebersihan'){
            $rekap = Keuangan::rekapKebersihan($bulan);

            return view("keuangan.laporan.pemakaian.generate.$data",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapKebersihan($rekap),
                'rincian'=>Keuangan::rincianKebersihan($bulan),
            ]);
        }

        if($data == 'airkotor'){
            return view("keuangan.laporan.pemakaian.generate.$data");
        }

        if($data == 'lain'){
            return view("keuangan.laporan.pemakaian.generate.$data");
        }

        if($data == 'tagihan'){
            $rekap = Keuangan::rekapTotal($bulan);

            return view("keuangan.laporan.pemakaian.generate.total",[
                'bln'=>IndoDate::bulanB($bulan,' '),
                'rekap'=>$rekap,
                'ttlRekap'=>Keuangan::ttlRekapTotal($rekap),
                'rincian'=>Keuangan::rincianTotal($bulan),
            ]);
        }
    }

    public function lapPendapatan(Request $request, $data){
        if($data == 'harian'){
            if($request->ajax())
            {
                $data = Pembayaran::orderBy('tgl_bayar','desc');
                return DataTables::of($data)
                ->editColumn('tgl_bayar', function ($data) {
                    return date('d-m-Y',strtotime($data->tgl_bayar));
                })
                ->editColumn('realisasi', function ($data) {
                    return number_format($data->realisasi);
                })
                ->make(true);
            }
            return view('keuangan.laporan.pendapatan.harian');
        }

        if($data == 'bulanan'){
            $dataTahun = Tagihan::select('thn_tagihan')
            ->groupBy('thn_tagihan')
            ->get();
            if($request->ajax())
            {
                $data = Pembayaran::select('bln_bayar')->groupBy('bln_bayar')->orderBy('bln_bayar','desc');
                return DataTables::of($data)
                ->addColumn('diskon', function($data){
                    $tagihan = Pembayaran::where('bln_bayar',$data->bln_bayar)
                    ->select(DB::raw('SUM(diskon) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
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
                ->editColumn('bln_bayar', function($data){
                    return IndoDate::bulanS($data->bln_bayar,' ');
                })
                ->make(true);
            }
            return view('keuangan.laporan.pendapatan.bulanan',['dataTahun' => $dataTahun]);
        }

        if($data == 'tahunan'){
            if($request->ajax())
            {
                $data = Pembayaran::select('thn_bayar')->groupBy('thn_bayar')->orderBy('thn_bayar','desc');
                return DataTables::of($data)
                ->addColumn('diskon', function($data){
                    $tagihan = Pembayaran::where('thn_bayar',$data->thn_bayar)
                    ->select(DB::raw('SUM(diskon) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
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
                ->make(true);
            }
            return view('keuangan.laporan.pendapatan.tahunan');
        }
    }

    public function lapGeneratePendapatan(Request $request, $data){
        if($data == 'harian'){
            $tanggal = $request->tgl_utama;
            $tgl = $tanggal;
            $tanggal = IndoDate::tanggal($tanggal, ' ');
            $cetak = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

            $data  = User::where('role','kasir')->get();
            $i = 0;
            $dataset = array();
            foreach($data as $d){
                $dataset[$i]['nama'] = $d->nama;

                $harian = Harian::where([['tgl_bayar',$tgl],['id_kasir',$d->id]])->get();
                $har_total  = 0;
                foreach($harian as $t){
                    $har_total = $har_total + $t->total;
                }
                $hari = $har_total;

                $bulanan = Pembayaran::where([['tgl_bayar',$tgl],['id_kasir',$d->id]])->get();
                $bul_total  = 0;
                foreach($bulanan as $b){
                    $bul_total = $bul_total + $b->realisasi;
                }
                $bulan = $bul_total;

                $dataset[$i]['bulanan'] = $bulan;
                $dataset[$i]['harian'] = $hari;
                $dataset[$i]['jumlah'] = $bulan + $hari;

                $i++;
            }

            $rincianbulan = Pembayaran::where('tgl_bayar',$tgl)->orderBy('kd_kontrol','asc')->get();

            return view('keuangan.laporan.pendapatan.generate.harian',[
                'dataset' => $dataset,
                'rincianbulan' => $rincianbulan,
                'cetak'   => $cetak,
                'tanggal' => $tanggal,
            ]);
        }

        if($data == 'bulanan'){
            $periode = $request->tahunpendapatan."-".$request->bulanpendapatan;
            $bln = IndoDate::bulan($periode, ' ');
            $cetak = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

            $data  = User::where('role','kasir')->get();
            $i = 0;
            $dataset = array();
            $rincianbulan = array();
            foreach($data as $d){
                $dataset[$i]['nama'] = $d->nama;

                $harian = Harian::where([['bln_bayar',$periode],['id_kasir',$d->id]])->get();
                $har_total  = 0;
                foreach($harian as $t){
                    $har_total = $har_total + $t->total;
                }
                $hari = $har_total;

                $bulanan = Pembayaran::where([['bln_bayar',$periode],['id_kasir',$d->id]])->get();
                $bul_total  = 0;
                foreach($bulanan as $b){
                    $bul_total = $bul_total + $b->realisasi;
                }
                $bulan = $bul_total;

                $dataset[$i]['bulanan'] = $bulan;
                $dataset[$i]['harian'] = $hari;
                $dataset[$i]['jumlah'] = $bulan + $hari;

                $rinbul = Pembayaran::where([['bln_bayar',$periode],['id_kasir',$d->id]])->select('tgl_bayar')->groupBy('tgl_bayar')->get();
                $j = 0;
                foreach($rinbul as $r){
                    $setor = Pembayaran::where([['tgl_bayar',$r->tgl_bayar],['id_kasir',$d->id]])->get();
                    $listrik = 0;
                    $denlistrik = 0;
                    $airbersih = 0;
                    $denairbersih = 0;
                    $keamananipk = 0;
                    $kebersihan = 0;
                    $airkotor = 0;
                    $lain = 0;
                    $jumlah = 0;
                    foreach($setor as $s){
                        $listrik = $listrik + $s->byr_listrik - $s->byr_denlistrik;
                        $denlistrik = $denlistrik + $s->byr_denlistrik;
                        $airbersih = $airbersih + $s->byr_airbersih - $s->byr_denairbersih;
                        $denairbersih = $denairbersih + $s->byr_denairbersih;
                        $keamananipk = $keamananipk + $s->byr_keamananipk;
                        $kebersihan = $kebersihan + $s->byr_kebersihan;
                        $airkotor = $airkotor + $s->byr_airkotor;
                        $lain = $lain + $s->byr_lain;
                        $jumlah = $listrik + $denlistrik + $airbersih + $denairbersih + $keamananipk + $kebersihan + $airkotor + $lain;
                    }
                    $rincianbulan[$i][$j]['nama']  = $d->nama;
                    $rincianbulan[$i][$j]['setor'] = $r->tgl_bayar;
                    $rincianbulan[$i][$j]['listrik'] = $listrik;
                    $rincianbulan[$i][$j]['denlistrik'] = $denlistrik;
                    $rincianbulan[$i][$j]['airbersih'] = $airbersih;
                    $rincianbulan[$i][$j]['denairbersih'] = $denairbersih;
                    $rincianbulan[$i][$j]['keamananipk'] = $keamananipk;
                    $rincianbulan[$i][$j]['kebersihan'] = $kebersihan;
                    $rincianbulan[$i][$j]['airkotor'] = $airkotor;
                    $rincianbulan[$i][$j]['lain'] = $lain;
                    $rincianbulan[$i][$j]['jumlah'] = $jumlah;
                    $j++;
                }

                $i++;
            }

            return view('keuangan.laporan.pendapatan.generate.bulanan',[
                'dataset' => $dataset,
                'rincianbulan' => $rincianbulan,
                'cetak'   => $cetak,
                'bulan'   => $bln,
            ]);
        }
    }

    public function lapRekap(Request $request, $data){
        if($data == 'sisa'){
            if($request->ajax()){
                $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->orderBy('bln_tagihan','desc');
                return DataTables::of($data)
                ->addColumn('ttl_tagihan', function($data){
                    $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                    ->select(DB::raw('SUM(ttl_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('dis_tagihan', function($data){
                    $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                    ->select(DB::raw('SUM(dis_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('rea_tagihan', function($data){
                    $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                    ->select(DB::raw('SUM(rea_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->addColumn('sel_tagihan', function($data){
                    $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                    ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                    if($tagihan != NULL){
                        return number_format($tagihan[0]->tagihan);
                    }
                    else{
                        return 0;
                    }
                })
                ->editColumn('bln_tagihan', function($data){
                    return IndoDate::bulan($data->bln_tagihan,' ');
                })
                ->make(true);
            }
            return view('keuangan.laporan.rekap.sisa');
        }

        if($data == 'selesai'){
            $dataTahun = Tagihan::select('thn_tagihan')
            ->groupBy('thn_tagihan')
            ->get();
            if($request->ajax()){
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
                ->editColumn('bln_bayar', function($data){
                    return IndoDate::bulanS($data->bln_bayar,' ');
                })
                ->make(true);
            }
            return view('keuangan.laporan.rekap.selesai',[
                'dataTahun' => $dataTahun,
            ]);
        }
    }

    public function lapGenerateRekap(Request $request, $data){
        if($data == 'sisa'){
            $bulan   = IndoDate::bulan(date('Y-m',strtotime(Carbon::now())),' ');
        
            $dataset = Tagihan::where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]])->select('blok')->groupBy('blok')->orderBy('blok','asc')->get();
            
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

            return view('keuangan.laporan.rekap.generate.sisa',[
                'dataset'   => $dataset,
                'tanggal'   => IndoDate::tanggal(date("Y-m-d",strtotime(Carbon::now())), " "),
                'rekap'     => $rekap,
                't_rekap'   => $t_rekap,
                'rincian'   => $rin
            ]);
        }

        if($data == 'selesai'){
            $cetak   = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));
            $periode = $request->tahunselesai."-".$request->bulanselesai;
            $data = Pembayaran::where('bln_bayar',$periode)->get();
            $listrik = 0;
            $denlistrik = 0;
            $airbersih = 0;
            $denairbersih = 0;
            $keamananipk = 0;
            $kebersihan = 0;
            $airkotor = 0;
            $lain = 0;
            foreach($data as $d){
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

            return view('keuangan.laporan.rekap.generate.selesai',[
                'dataset' => $dataset,
                'rincian' => $rincian,
                'bulan'   => $bulan,
                'cetak'   => $cetak
            ]);
        }
    }

    public function dataTagihan(Request $request){
        if($request->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->orderBy('bln_tagihan','desc');
            return DataTables::of($data)
            ->addColumn('ttl_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(ttl_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('dis_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(dis_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('rea_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(rea_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->addColumn('sel_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->editColumn('bln_tagihan', function($data){
                return IndoDate::bulan($data->bln_tagihan,' ');
            })
            ->make(true);
        }
        return view('keuangan.data.tagihan');
    }

    public function dataTunggakan(Request $request){
        if($request->ajax()){
            $data = Tagihan::select('bln_tagihan')->groupBy('bln_tagihan')->orderBy('bln_tagihan','desc');
            return DataTables::of($data)
            ->addColumn('sel_tagihan', function($data){
                $tagihan = Tagihan::where('bln_tagihan',$data->bln_tagihan)
                ->select(DB::raw('SUM(sel_tagihan) as tagihan'))->get();
                if($tagihan != NULL){
                    return number_format($tagihan[0]->tagihan);
                }
                else{
                    return 0;
                }
            })
            ->editColumn('bln_tagihan', function($data){
                return IndoDate::bulan($data->bln_tagihan,' ');
            })
            ->make(true);
        }
        return view('keuangan.data.tunggakan');
    }

    public function arsip(Request $request){
        $tanggal = $request->tanggal;
        $fasilitas = $request->fasilitasarsip;
        $cetak = IndoDate::tanggal(date('Y-m-d',strtotime(Carbon::now())),' ')." ".date('H:i:s',strtotime(Carbon::now()));

        if($fasilitas == 'listrik'){
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_listrik','>',0]])->orderBy('kd_kontrol','asc')->get();
            return view('keuangan.arsip.listrik',[
                'dataset'   => $dataset, 
                'tanggal'   => IndoDate::tanggal($tanggal," "),
                'cetak'     => $cetak,
                'fasilitas' => "Listrik"
            ]);
        }

        if($fasilitas == 'airbersih'){
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_airbersih','>',0]])->orderBy('kd_kontrol','asc')->get();
            return view('keuangan.arsip.airbersih',[
                'dataset'   => $dataset, 
                'tanggal'   => IndoDate::tanggal($tanggal," "),
                'cetak'     => $cetak,
                'fasilitas' => "Air Bersih"
            ]);
        }

        if($fasilitas == 'keamananipk'){
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_keamananipk','>',0]])->orderBy('kd_kontrol','asc')->get();
            return view('keuangan.arsip.keamananipk',[
                'dataset'   => $dataset, 
                'tanggal'   => IndoDate::tanggal($tanggal," "),
                'cetak'     => $cetak,
                'fasilitas' => "Keamanan & IPK"
            ]);
        }

        if($fasilitas == 'kebersihan'){
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_kebersihan','>',0]])->orderBy('kd_kontrol','asc')->get();
            return view('keuangan.arsip.kebersihan',[
                'dataset'   => $dataset, 
                'tanggal'   => IndoDate::tanggal($tanggal," "),
                'cetak'     => $cetak,
                'fasilitas' => "Kebersihan"
            ]);
        }

        if($fasilitas == 'airkotor'){
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_airkotor','>',0]])->orderBy('kd_kontrol','asc')->get();
            return view('keuangan.arsip.airkotor',[
                'dataset'   => $dataset, 
                'tanggal'   => IndoDate::tanggal($tanggal," "),
                'cetak'     => $cetak,
                'fasilitas' => "Air Kotor"
            ]);
        }

        if($fasilitas == 'lain'){
            $dataset = Pembayaran::where([['tgl_bayar',$tanggal],['byr_lain','>',0]])->orderBy('kd_kontrol','asc')->get();
            return view('keuangan.arsip.lain',[
                'dataset'   => $dataset, 
                'tanggal'   => IndoDate::tanggal($tanggal," "),
                'cetak'     => $cetak,
                'fasilitas' => "Lain - Lain"
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
