<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukPembayaran extends Model
{
    use HasFactory;
    protected $table ='struk_pembayaran';
    protected $fillable = [
        'id',
        'ref',
        'tgl_bayar',
        'bln_bayar',
        'nomor',
        'kd_kontrol',
        'pedagang',
        'los',
        'lokasi',
        'taglistrik',
        'tagtunglistrik',
        'tagdenlistrik',
        'tagawlistrik',
        'tagaklistrik',
        'tagdylistrik',
        'tagpklistrik',
        'tagairbersih',
        'tagtungairbersih',
        'tagdenairbersih',
        'tagawairbersih',
        'tagakairbersih',
        'tagpkairbersih',
        'tagkeamananipk',
        'tagtungkeamananipk',
        'tagkebersihan',
        'tagtungkebersihan',
        'tagairkotor',
        'tagtungairkotor',
        'taglain',
        'tagtunglain',
        'totalTagihan',
        'bayar',
        'kasir',
        'cetakan',
        'updated_at',
        'created_at'
    ];
}
