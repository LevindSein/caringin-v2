<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
