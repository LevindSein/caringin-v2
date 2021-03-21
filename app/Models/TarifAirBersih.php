<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifAirBersih extends Model
{
    use HasFactory;
    protected $table ='trf_air_bersih';
    protected $fillable = ['id','trf_1','trf_2','trf_pemeliharaan','trf_beban','trf_arkot','trf_denda','trf_ppn','trf_pasang','updated_at','created_at'];
}
