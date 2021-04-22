<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IndoDate;
use App\Models\Tunggakan;

class TunggakanController extends Controller
{
    public function __construct()
    {
        $this->middleware('tunggakan');
    }

    public function index(){
        return view('laporan.tunggakan.index',[
            'dataset'=>Tunggakan::data()
        ]);
    }

    public function fasilitas(Request $request){

    }
}
