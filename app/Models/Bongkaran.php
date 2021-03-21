<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bongkaran extends Model
{
    use HasFactory;
    protected $table ='bongkaran';
    protected $fillable = [
        'id',
        'tgl_bongkar',
        'kd_kontrol',
        'listrik',
        'air',
        'via_bongkar',
        'updated_at',
        'created_at'
    ];
}
