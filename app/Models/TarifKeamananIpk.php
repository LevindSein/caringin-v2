<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifKeamananIpk extends Model
{
    use HasFactory;
    protected $table ='trf_keamanan_ipk';
    protected $fillable = ['id','tarif','prs_keamanan','prs_ipk','updated_at','created_at'];
}
