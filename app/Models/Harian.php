<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harian extends Model
{
    use HasFactory;
    protected $table = 'pembayaran_harian';
    protected $fillable = [
        'id',
        'ref',
        'tgl_bayar',
        'bln_bayar',
        'thn_bayar',
        'ket',
        'json',
        'total',
        'id_kasir',
        'kasir',
        'updated_at',
        'created_at'
    ];
}
