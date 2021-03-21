<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlatListrik extends Model
{
    use HasFactory;
    protected $table ='meteran_listrik';
    protected $fillable = ['id','kode','nomor','akhir','daya','stt_sedia','stt_bayar','updated_at','created_at'];
}
