<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\TempatUsaha;

class Pedagang extends Model
{
    use HasFactory;
    protected $table ='user';
    protected $fillable = [
        'id', 
        'username', 
        'nama', 
        'anggota', 
        'ktp', 
        'npwp', 
        'alamat',
        'email', 
        'hp', 
        'password', 
        'remember_token', 
        'role', 
        'stt_aktif',
        'updated_at', 
        'created_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otoritas',
    ];

    public static function nasabah($id, $status){
        if($status == 'pemilik')
            $data = TempatUsaha::where('id_pemilik',$id)->select('kd_kontrol')->get();

        if($status == 'pengguna')
            $data = TempatUsaha::where('id_pengguna',$id)->select('kd_kontrol')->get();

        if($data != NULL){
            $nasabah = array();
            $i = 0;
            foreach($data as $d){
                $nasabah[$i] = $d->kd_kontrol; 
                $i++;
            }
            return $nasabah;
        }
        else{
            return NULL;
        }
    }
}
