<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sinkronisasi extends Model
{
    use HasFactory;
    protected $table = 'sinkronisasi';
    protected $fillable = ['id','sinkron','faktur','surat','updated_at','created_at'];
}
