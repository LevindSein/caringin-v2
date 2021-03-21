<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neraca extends Model
{
    use HasFactory;

    protected $table ='neraca';
    protected $fillable = [
        'id', 
        'tanggal', 
        'bulan', 
        'tahun',  
        'listrik',
        'airbersih',
        'keamananipk',
        'kebersihan',
        'airkotor',
        'lain',
        'debit',
        'kredit', 
        'saldo', 
        'sisa',
        'updated_at', 
        'created_at'
    ];
}
