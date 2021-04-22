<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tunggakan extends Model
{
    use HasFactory;

    public static function data(){
        return Tagihan::select('bln_tagihan')
        ->groupBy('bln_tagihan')
        ->orderBy('bln_tagihan','desc')
        ->get();
    }
}
