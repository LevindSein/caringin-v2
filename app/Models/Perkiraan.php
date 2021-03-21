<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perkiraan extends Model
{
    use HasFactory;
    protected $table = 'data_perkiraan';
    protected $fillable = [
        'id',
        'kode',
        'jenis',
        'nama',
        'updated_at',
        'created_at'
    ];
}
