<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table ='pembayaran';
    protected $fillable = [
        'id',
        'no_faktur',
        'ref',
        'tgl_bayar',
        'bln_bayar',
        'thn_bayar',
        'tgl_tagihan',
        'via_bayar',
        'id_kasir',
        'nama',
        'blok',
        'kd_kontrol',
        'pengguna',
        'id_tagihan',
        'byr_listrik',
        'byr_denlistrik',
        'sel_listrik',
        'dis_listrik',
        'byr_airbersih',
        'byr_denairbersih',
        'sel_airbersih',
        'dis_airbersih',
        'byr_keamananipk',
        'byr_keamanan',
        'byr_ipk',
        'sel_keamananipk',
        'dis_keamananipk',
        'byr_kebersihan',
        'sel_kebersihan',
        'dis_kebersihan',
        'byr_airkotor',
        'sel_airkotor',
        'byr_lain',
        'sel_lain',
        'sub_tagihan',
        'diskon',
        'ttl_tagihan',
        'realisasi',
        'sel_tagihan',
        'stt_denda',
        'shift',
        'updated_at',
        'created_at'
    ];
}
