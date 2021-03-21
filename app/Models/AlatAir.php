<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatAir extends Model
{
    use HasFactory;
    protected $table ='meteran_air';
    protected $fillable = ['id','kode','nomor','akhir','stt_sedia','stt_bayar','updated_at','created_at'];
}
