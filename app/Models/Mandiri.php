<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mandiri extends Model
{
    use HasFactory;
    protected $table = 'mandiri';
    protected $fillable = ['id','kode','number','currency','name','periode','jth_tempo','open','close','bill','updated_at','created_at'];
}
