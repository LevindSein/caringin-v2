<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

//Keuangan
use App\Http\Controllers\KeuanganController;

//Kasir
use App\Http\Controllers\KasirController;

//Dashboard
use App\Http\Controllers\DashboardController;

//Layanan
use App\Http\Controllers\LayananController;

//Pedagang
use App\Http\Controllers\PedagangController;

//Tempat Usaha
use App\Http\Controllers\TempatController;

//Tagihan
use App\Http\Controllers\TagihanController;

//Potensi
use App\Http\Controllers\PotensiController;

//Laporan
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PendapatanController;
use App\Http\Controllers\TunggakanController;

//Data Usaha
use App\Http\Controllers\DataUsahaController;

//Tarif
use App\Http\Controllers\TarifController;

//Alat Meter
use App\Http\Controllers\AlatController;

//Hari Libur Tagihan
use App\Http\Controllers\HariLiburController;

//Blok
use App\Http\Controllers\BlokController;

//Simulasi
use App\Http\Controllers\SimulasiController;

//Master
use App\Http\Controllers\MasterController;

//User
use App\Http\Controllers\UserController;

//Information
use App\Http\Controllers\InformationController;

//Saran
use App\Http\Controllers\SaranController;

//Setting
use App\Http\Controllers\SettingController;

//Search
use App\Http\Controllers\SearchController;

//Working time
use App\Http\Controllers\WorkController;

//Login
use App\Models\LoginLog;
use App\Models\LevindCrypt;

//Time
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', function(){
    $time = "unknown";
    $now = Carbon::now();
    $start = Carbon::createFromTimeString('04:00');
    $end = Carbon::createFromTimeString('10:00');
    if ($now->between($start, $end)){
        $time = "pagi";
    }
    $start = Carbon::createFromTimeString('10:00');
    $end = Carbon::createFromTimeString('15:00');
    if ($now->between($start, $end)){
        $time = "siang";
    }
    $start = Carbon::createFromTimeString('15:00');
    $end = Carbon::createFromTimeString('19:00');
    if ($now->between($start, $end)){
        $time = "sore";
    }
    $start = Carbon::createFromTimeString('19:00');
    $end = Carbon::createFromTimeString('23:59');
    if ($now->between($start, $end)){
        $time = "malam";
    }
    $start = Carbon::createFromTimeString('00:00');
    $end = Carbon::createFromTimeString('04:00');
    if ($now->between($start, $end)){
        $time = "malam";
    }

    return view('home.login',['time'=>$time]);
})->name('login');

//LOGIN
Route::post('storelogin',function(Request $request){
    try{
        if($request->role === 'master')
            return redirect()->route('dashboard')->with('success',"Selamat Datang $request->nama");
        else if($request->role === 'manajer')
            return redirect()->route('dashboard')->with('success',"Selamat Datang $request->nama");
        else if($request->role === 'kasir')
            return redirect()->route('kasir.index');
        else if($request->role === 'admin')
            return redirect()->route('dashboard')->with('success',"Selamat Datang $request->nama");
        else if($request->role === 'keuangan')
            return redirect()->route('dashboard')->with('success',"Selamat Datang $request->nama");
        else
            abort(419);
    }
    catch(\Exception $e){
        abort(404);
    }
})->middleware('ceklogin:home');

//LOGOUT
Route::get('logout',function(){
    Session::flush();
    Artisan::call('cache:clear');
    return redirect()->route('login')->with('success','Sampai Jumpa Lagi');
});

Route::middleware('ceklogin:keuangan')->group(function(){
    Route::get('keuangan/rekap/show/{fasilitas}/{data}',[KeuanganController::class, 'rekapDetails']);
    Route::post('keuangan/rekap/generate',[KeuanganController::class, 'rekapGenerate']);
    Route::get('keuangan/rekap/{data}',[KeuanganController::class, 'rekap']);
    Route::get('keuangan/pendapatan/show/{fasilitas}/{data}',[KeuanganController::class, 'pendapatanDetails']);
    Route::post('keuangan/pendapatan/generate',[KeuanganController::class, 'pendapatanGenerate']);
    Route::get('keuangan/pendapatan/{data}',[KeuanganController::class, 'pendapatan']);
    Route::post('keuangan/tunggakan/generate',[KeuanganController::class, 'tunggakanGenerate']);
    Route::get('keuangan/tunggakan/{data}',[KeuanganController::class, 'tunggakan']);
    Route::get('keuangan/tagihan/show/{data}',[KeuanganController::class, 'tagihanDetails']);
    Route::post('keuangan/tagihan/generate',[KeuanganController::class, 'tagihanGenerate']);
    Route::get('keuangan/tagihan/{data}',[KeuanganController::class, 'tagihan']);
});

Route::middleware('ceklogin:kasir')->group(function(){
    Route::post('kasir/printer/{printer}',[KasirController::class, 'printer']);
    Route::get('kasir/utama',[KasirController::class, 'getutama']);
    Route::get('kasir/utama/bulan',[KasirController::class, 'getutamaBulan']);

    Route::get('kasir/sisa',[KasirController::class, 'getsisa']);
    Route::get('kasir/selesai',[KasirController::class, 'getselesai']);

    Route::get('kasir/penerimaan',[KasirController::class, 'penerimaan']);

    Route::get('kasir/restore',[KasirController::class, 'restore']);
    Route::post('kasir/restore/{id}',[KasirController::class, 'restoreStore']);

    Route::get('kasir/struk/{struk}',[KasirController::class, 'struk']);
    Route::get('kasir/struk/{struk}/{id}',[KasirController::class, 'cetakStruk']);

    Route::get('kasir/bayar/{data}',[KasirController::class, 'bayar']);
    Route::get('kasir/rincian/{kontrol}',[KasirController::class, 'rincian']);
    Route::resource('kasir', KasirController::class);
});


Route::middleware('ceklogin:dashboard')->group(function () {
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('ceklogin:layanan')->group(function (){
    Route::get('layanan/bongkaran/generate', [LayananController::class, 'bongkaranGenerate']);
    Route::get('layanan/bongkaran/show/{id}', [LayananController::class, 'bongkaranShow']);
    Route::post('layanan/bongkaran/surat', [LayananController::class, 'bongkaranSurat']);
    Route::post('layanan/bongkaran/selesai/{id}', [LayananController::class, 'bongkaranSelesai']);
    Route::get('layanan/bongkaran', [LayananController::class, 'bongkaranIndex']);
    Route::get('layanan/alatmeter/riwayat', [LayananController::class, 'alatMeterRiwayat']);
    Route::get('layanan/alatmeter/bongkar', [LayananController::class, 'alatMeterBongkar']);
    Route::get('layanan/alatmeter/ganti', [LayananController::class, 'alatMeterGanti']);
    Route::get('layanan/alatmeter', [LayananController::class, 'alatMeterIndex']);
    Route::get('layanan/nasabah', [LayananController::class, 'nasabahIndex']);
});

Route::middleware('ceklogin:pedagang')->group(function (){
    Route::get('pedagang/generate', [PedagangController::class, 'generate']);
    Route::get('pedagang/{id}', [PedagangController::class, 'show']);
    Route::post('pedagang/update', [PedagangController::class, 'update']);
    Route::get('pedagang/destroy/{id}', [PedagangController::class, 'destroy']);
    Route::resource('pedagang', PedagangController::class);
});

Route::middleware('ceklogin:tempatusaha')->group(function (){
    Route::get('tempatusaha/show/{id}', [TempatController::class, 'show']);
    Route::get('tempatusaha/alat', [TempatController::class, 'alat']);
    Route::get('tempatusaha/qr/{id}',[TempatController::class, 'qr']);
    Route::get('tempatusaha/rekap', [TempatController::class, 'rekap']);
    Route::get('tempatusaha/rekap/{blok}',[TempatController::class, 'rekapdetail']);
    Route::get('tempatusaha/fasilitas/{fas}',[TempatController::class, 'fasilitas']);
    Route::post('tempatusaha/update', [TempatController::class, 'update']);
    Route::get('tempatusaha/destroy/{id}', [TempatController::class, 'destroy']);
    Route::resource('tempatusaha', TempatController::class);
});

Route::middleware('ceklogin:tagihan')->group(function (){
    Route::get('penghapusan/show/{fas}/{id}', [TagihanController::class, 'showPenghapusan']);
    Route::get('tagihan/show/{fas}/{id}', [TagihanController::class, 'show']);
    Route::post('tagihan/update', [TagihanController::class, 'update']);
    Route::get('tagihan/{id}/edit', [TagihanController::class, 'edit']);
    Route::get('tagihan/check/manual', [TagihanController::class, 'checkManual']);
    Route::post('tagihan/manual', [TagihanController::class, 'manual']);
    Route::post('tagihan/refresh/tarif', [TagihanController::class, 'refreshTarif']);
    Route::get('tagihan/listrik', [TagihanController::class, 'listrik'])->name('listrik');
    Route::get('tagihan/airbersih', [TagihanController::class, 'airbersih'])->name('airbersih');
    Route::post('tagihan/listrik', [TagihanController::class, 'listrikUpdate']);
    Route::post('tagihan/airbersih', [TagihanController::class, 'airbersihUpdate']);
    Route::post('tagihan/pembayaran', [TagihanController::class, 'pembayaran']);
    Route::post('tagihan/pemberitahuan', [TagihanController::class, 'pemberitahuan']);
    Route::get('tagihan/print', [TagihanController::class, 'print']);
    Route::get('tagihan/tempat', [TagihanController::class, 'tempat']);
    Route::post('tagihan/publish', [TagihanController::class, 'publish']);
    Route::get('tagihan/notif/edit/{id}', [TagihanController::class, 'notifEdit']);
    Route::post('tagihan/notif/{id}', [TagihanController::class, 'notif']);
    Route::post('tagihan/unpublish/{id}', [TagihanController::class, 'unpublish']);
    Route::get('tagihan/penghapusan', [TagihanController::class, 'penghapusan']);
    Route::post('tagihan/penghapusan/{id}', [TagihanController::class, 'penghapusanRestore']);
    Route::get('tagihan/destroy/edit/{id}', [TagihanController::class, 'destroyEdit']);
    Route::post('tagihan/destroy/{id}', [TagihanController::class, 'destroy']);
    Route::post('tagihan/sync/synchronize/{tanggal}',[TagihanController::class, 'synchronize']);
    Route::post('tagihan/sync/unsynchronize/{tanggal}',[TagihanController::class, 'unsynchronize']);
    Route::get('tagihan/sync/initiate',[TagihanController::class, 'initiate']);
    Route::get('tagihan/sync/badge',[TagihanController::class, 'badge']);
    Route::get('tagihan', [TagihanController::class, 'index'])->name('tagihan');
});

Route::middleware('ceklogin:potensi')->group(function () {
    Route::post('potensi/tempatusaha/download',[PotensiController::class, 'tempatUsahaDownload']);
    Route::get('potensi/tempatusaha',[PotensiController::class, 'tempatUsaha']);

    Route::get('potensi/tagihan',[PotensiController::class, 'tagihan']);
});

Route::middleware('ceklogin:pemakaian')->group(function(){
    Route::get('rekap/pemakaian', [PemakaianController::class, 'index']);
    Route::post('rekap/pemakaian',[PemakaianController::class, 'fasilitas']);
});

Route::middleware('ceklogin:pendapatan')->group(function(){
    Route::post('rekap/pendapatan/generate', [PendapatanController::class, 'generate']);
    Route::get('rekap/pendapatan/show/{fas}/{id}', [PendapatanController::class, 'show']);
    Route::get('rekap/pendapatan/tahunan', [PendapatanController::class, 'tahunan']);
    Route::get('rekap/pendapatan/bulanan', [PendapatanController::class, 'bulanan']);
    Route::resource('rekap/pendapatan', PendapatanController::class);
});

Route::middleware('ceklogin:tunggakan')->group(function(){
    Route::get('rekap/tunggakan', [TunggakanController::class, 'index']);
    Route::post('rekap/tunggakan',[TunggakanController::class, 'fasilitas']);
});

Route::middleware('ceklogin:datausaha')->group(function(){
    Route::get('datausaha/show/details/{bln}', [DataUsahaController::class, 'show']);
    Route::get('datausaha/pendapatan', [DataUsahaController::class, 'pendapatan']);
    Route::get('datausaha/tunggakan', [DataUsahaController::class, 'tunggakan']);
    Route::get('datausaha', [DataUsahaController::class, 'index']);
});

Route::middleware('ceklogin:tarif')->group(function(){
    Route::get('utilities/tarif', [TarifController::class, 'index']);
    Route::get('utilities/tarif/keamananipk', [TarifController::class, 'keamananipk']);
    Route::get('utilities/tarif/kebersihan', [TarifController::class, 'kebersihan']);
    Route::get('utilities/tarif/airkotor', [TarifController::class, 'airkotor']);
    Route::get('utilities/tarif/lain', [TarifController::class, 'lain']);
    Route::post('utilities/tarif/store', [TarifController::class, 'store']);
    Route::get('utilities/tarif/edit/{fasilitas}/{id}', [TarifController::class, 'edit']);
    Route::post('utilities/tarif/update', [TarifController::class, 'update']);
    Route::get('utilities/tarif/destroy/{fasilitas}/{id}', [TarifController::class, 'destroy']);
});

Route::middleware('ceklogin:alatmeter')->group(function(){
    Route::get('utilities/alatmeter', [AlatController::class, 'index']);
    Route::get('utilities/alatmeter/air', [AlatController::class, 'air']);
    Route::post('utilities/alatmeter/store', [AlatController::class, 'store']);
    Route::get('utilities/alatmeter/edit/{fasilitas}/{id}', [AlatController::class, 'edit']);
    Route::post('utilities/alatmeter/update', [AlatController::class, 'update']);
    Route::get('utilities/alatmeter/destroy/{fasilitas}/{id}', [AlatController::class, 'destroy']);
    Route::get('utilities/alatmeter/qr/{fasilitas}/{id}', [AlatController::class, 'qr']);
});

Route::middleware('ceklogin:harilibur')->group(function(){
    Route::get('utilities/harilibur', [HariLiburController::class, 'index']);
    Route::post('utilities/harilibur/store', [HariLiburController::class, 'store']);
    Route::get('utilities/harilibur/edit/{id}', [HariLiburController::class, 'edit']);
    Route::post('utilities/harilibur/update', [HariLiburController::class, 'update']);
    Route::get('utilities/harilibur/destroy/{id}', [HariLiburController::class, 'destroy']);
});

Route::middleware('ceklogin:blok')->group(function(){
    Route::get('utilities/blok', [BlokController::class, 'index']);
    Route::post('utilities/blok/store', [BlokController::class, 'store']);
    Route::get('utilities/blok/edit/{id}', [BlokController::class, 'edit']);
    Route::post('utilities/blok/update', [BlokController::class, 'update']);
    Route::get('utilities/blok/destroy/{id}', [BlokController::class, 'destroy']);
});

Route::middleware('ceklogin:simulasi')->group(function(){
    Route::get('utilities/simulasi', [SimulasiController::class, 'index']);
    Route::post('utilities/simulasi', [SimulasiController::class, 'store']);
});

Route::middleware('ceklogin:master')->group(function(){
    Route::get('master/kasir/sisa',[MasterController::class, 'getsisa']);
    Route::get('master/kasir', [MasterController::class, 'kasir']);
    Route::post('master/kasir/restore/{id}', [MasterController::class, 'kasirRestore']);
    Route::post('master/kasir/edit', [MasterController::class, 'kasirEdit']);
    Route::get('master/kasir/struk/{struk}/{id}',[MasterController::class, 'cetakStruk']);
});

Route::middleware('ceklogin:user')->group(function(){
    Route::get('user/details/{id}', [PedagangController::class, 'show']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::get('user/destroy/{id}', [UserController::class, 'destroy']);
    Route::post('user/reset/{id}', [UserController::class, 'reset']);
    Route::get('user/{id}/kasir', [UserController::class, 'kasirEtoritas']);
    Route::post('user/otoritas/kasir', [UserController::class, 'kasirOtoritas']);
    Route::get('user/{id}/otoritas', [UserController::class, 'etoritas']);
    Route::post('user/otoritas', [UserController::class, 'otoritas']);
    Route::get('user/manajer', [UserController::class, 'manajer']);
    Route::get('user/keuangan', [UserController::class, 'keuangan']);
    Route::get('user/kasir', [UserController::class, 'kasir']);
    Route::get('user/nasabah', [UserController::class, 'nasabah']);
    Route::resource('user', UserController::class);
});

Route::middleware('ceklogin:log')->group(function(){
    Route::get('log',function(Request $request){
        if(request()->ajax())
        {
            $data = LoginLog::orderBy('id','desc');
            return DataTables::of($data)
                    ->editColumn('ktp', function ($ktp) {
                        if ($ktp->ktp == NULL) return '<span class="text-center"><i class="fas fa-times fa-sm"></i></span>';
                        else return $ktp->ktp;
                    })
                    ->editColumn('created_at', function ($user) {
                        return [
                           'display' => $user->created_at->format('d-m-Y H:i:s'),
                           'timestamp' => $user->created_at->timestamp
                        ];
                     })
                    ->rawColumns(['ktp'])
                    ->make(true);
        }
        return view('log.index');
    })->middleware('log');
});

Route::middleware('ceklogin:information')->group(function(){
    Route::post('information/update', [InformationController::class, 'update']);
    Route::get('information/destroy/{id}', [InformationController::class, 'destroy']);
    Route::resource('information', InformationController::class);
});

Route::middleware('ceklogin:saran')->group(function(){
    Route::post('saran/confirm/{id}', [SaranController::class, 'confirm']);
    Route::resource('saran', SaranController::class);
});

Route::middleware('ceklogin:settings')->group(function(){
    Route::resource('settings', SettingController::class);
});

Route::middleware('ceklogin:human')->group(function(){
    Route::get('cari/blok',[SearchController::class, 'cariBlok']);
    Route::get('cari/nasabah',[SearchController::class, 'cariNasabah']);
    Route::get('cari/alamat',[SearchController::class, 'cariAlamat']);
    Route::get('cari/alamat/kosong',[SearchController::class, 'cariAlamatKosong']);
    Route::get('cari/alatlistrik',[SearchController::class, 'cariAlatListrik']);
    Route::get('cari/alatair',[SearchController::class, 'cariAlatAir']);
    Route::get('cari/tagihan/{id}',[SearchController::class, 'cariTagihan']);
    Route::get('cari/listrik/{id}',[SearchController::class, 'cariListrik']);
    Route::get('cari/airbersih/{id}',[SearchController::class, 'cariAirBersih']);
    Route::get('cari/keamananipk/{id}',[SearchController::class, 'cariKeamananIpk']);
    Route::get('cari/kebersihan/{id}',[SearchController::class, 'cariKebersihan']);
    Route::get('cari/airkotor/{id}',[SearchController::class, 'cariAirKotor']);
    Route::get('cari/lain/{id}',[SearchController::class, 'cariLain']);
    Route::get('cari/tagihan/{fasilitas}/{kontrol}',[SearchController::class, 'cariTagihanku']);
});

Route::get('work',[WorkController::class, 'work']);
Route::post('work',[WorkController::class, 'update']);

Route::get('optimize.p3cmaster',function(){
    Artisan::call('optimize');
    Artisan::call('cron:log');
    Artisan::call('cron:login');
    return "Oops";
});

Route::get('encrypt/{value}',function ($value){
    if(request()->ajax()){
        if($value == 'value')
            return response()->json(['result' => LevindCrypt::encryptString(0)]);
    }
});
