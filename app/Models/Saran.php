<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saran extends Model
{
    use HasFactory;
    protected $table = 'saran';
    protected $fillable = ['id','tanggal','nama','email','hp','keterangan','status','updated_at','created_at'];
}
