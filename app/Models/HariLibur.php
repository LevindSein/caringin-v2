<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    use HasFactory;
    protected $table = 'hari_libur';
    protected $fillable = ['id','tanggal','ket','updated_at','created_at'];
}
