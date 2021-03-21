<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;
    protected $table ='dokumen';
    protected $fillable = [
        'id',
        'kd_kontrol',
        'nama',
        'tgl_tagihan',
        'srt_permohonan',
        'srt_acara',
        'srt_bayar',
        'srt_pasang',
        'srt_peringatan',
        'srt_bongkar',
        'stt_surat',
        'keterangan',
        'updated_at',
        'created_at'
    ];
}
