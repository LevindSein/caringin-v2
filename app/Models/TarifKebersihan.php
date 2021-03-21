<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifKebersihan extends Model
{
    use HasFactory;
    protected $table ='trf_kebersihan';
    protected $fillable = ['id','tarif','updated_at','created_at'];
}
