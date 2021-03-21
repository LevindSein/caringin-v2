<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;
    
    protected $table ='login_log';
    protected $fillable = [
        'id', 
        'username', 
        'nama', 
        'ktp',  
        'hp', 
        'role', 
        'platform',
        'updated_at', 
        'created_at'
    ];
}
