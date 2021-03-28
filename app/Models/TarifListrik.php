<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifListrik extends Model
{
    use HasFactory;
    protected $table ='trf_listrik';
    protected $fillable = ['id','trf_rekmin','trf_beban','trf_blok1','trf_blok2','trf_standar','trf_bpju','trf_denda','trf_denda_lebih','trf_ppn','trf_pasang','updated_at','created_at'];
}
