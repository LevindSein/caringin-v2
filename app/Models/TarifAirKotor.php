<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifAirKotor extends Model
{
    use HasFactory;
    protected $table ='trf_air_kotor';
    protected $fillable = ['id','tarif','updated_at','created_at'];
}
