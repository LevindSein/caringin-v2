<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

//Dashboard
use App\Http\Controllers\DashboardController;

//Pedagang
use App\Http\Controllers\PedagangController;

//Tempat Controller
use App\Http\Controllers\TempatController;

//Search
use App\Http\Controllers\SearchController;

//Working time
use App\Http\Controllers\WorkController;

//Login
use App\Models\User;
use App\Models\LoginLog;

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
        if(csrf_token() === $request->_token){
            if($request->role === 'master')
                return redirect()->route('dashboard')->with('success','Selamat Datang Master');
            else if($request->role === 'manajer')
                return redirect()->route('dashboard')->with('success','Selamat Datang Manajer');
            else if($request->role === 'kasir')
                return redirect()->route('kasir.index');
            else if($request->role === 'admin')
                return redirect()->route('dashboard')->with('success',"Selamat Datang $request->nama");
            else if($request->role === 'keuangan')
                return redirect()->route('keuangan.index');
            else
                abort(404);
        }
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

Route::middleware('ceklogin:dashboard')->group(function () {
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('ceklogin:pedagang')->group(function (){
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
Route::post('work/update',[WorkController::class, 'update']);

Route::get('optimize.p3cmaster',function(){
    Artisan::call('optimize');
    Artisan::call('cron:log');
    Artisan::call('cron:login');
    return view('danger');
});