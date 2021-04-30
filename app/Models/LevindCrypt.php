<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class LevindCrypt extends Model
{
    use HasFactory;

    public static function encryptString($crypt)
    {
        return Crypt::encryptString($crypt).Session::get('cryptString');
    }

    public static function decryptString($crypt)
    {
        $crypt = substr($crypt, 0, strpos($crypt, Session::get('cryptString')));
        return Crypt::decryptString($crypt);
    }
}
