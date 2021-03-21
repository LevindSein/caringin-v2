<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penghapusan extends Model
{
    use HasFactory;
    protected $table ='penghapusan';
    protected $fillable = [
        'id',
        'nama',
        'blok',
        'kd_kontrol',
        'bln_pakai',
        'tgl_tagihan',
        'bln_tagihan',
        'thn_tagihan',
        'tgl_expired',
        'stt_lunas',
        'stt_bayar',
        'awal_airbersih',
        'akhir_airbersih',
        'pakai_airbersih',
        'byr_airbersih',
        'pemeliharaan_airbersih',
        'beban_airbersih',
        'arkot_airbersih',
        'sub_airbersih',
        'dis_airbersih',
        'ttl_airbersih',
        'rea_airbersih',
        'sel_airbersih',
        'den_airbersih',
        'daya_listrik',
        'awal_listrik',
        'akhir_listrik',
        'pakai_listrik',
        'byr_listrik',
        'rekmin_listrik',
        'blok1_listrik',
        'blok2_listrik',
        'beban_listrik',
        'bpju_listrik',
        'sub_listrik',
        'dis_listrik',
        'ttl_listrik',
        'rea_listrik',
        'sel_listrik',
        'den_listrik',
        'jml_alamat',
        'sub_keamananipk',
        'dis_keamananipk',
        'ttl_keamananipk',
        'ttl_keamanan',
        'ttl_ipk',
        'rea_keamananipk',
        'sel_keamananipk',
        'sub_kebersihan',
        'dis_kebersihan',
        'ttl_kebersihan',
        'rea_kebersihan',
        'sel_kebersihan',
        'ttl_airkotor',
        'rea_airkotor',
        'sel_airkotor',
        'ttl_lain',
        'rea_lain',
        'sel_lain',
        'sub_tagihan',
        'dis_tagihan',
        'ttl_tagihan',
        'rea_tagihan',
        'sel_tagihan',
        'den_tagihan',
        'stt_denda',
        'stt_kebersihan',
        'stt_keamananipk',
        'stt_listrik',
        'stt_airbersih',
        'stt_airkotor',
        'stt_lain',
        'ket',
        'via_tambah',
        'via_hapus',
        'updated_at',
        'created_at'
    ];

    public static function totalTagihan($id){
        $tagihan = self::find($id);
        //Subtotal
        $subtotal = 
                    $tagihan->sub_listrik     + 
                    $tagihan->sub_airbersih   + 
                    $tagihan->sub_keamananipk + 
                    $tagihan->sub_kebersihan  + 
                    $tagihan->ttl_airkotor    + 
                    $tagihan->ttl_lain;
        $tagihan->sub_tagihan = $subtotal;
        
        //Diskon
        $diskon = 
                $tagihan->dis_listrik     + 
                $tagihan->dis_airbersih   + 
                $tagihan->dis_keamananipk + 
                $tagihan->dis_kebersihan;
        $tagihan->dis_tagihan = $diskon;

        //Denda
        $tagihan->den_tagihan = $tagihan->den_listrik + $tagihan->den_airbersih;
        
        //TOTAL
        $total = 
                $tagihan->ttl_listrik     + 
                $tagihan->ttl_airbersih   + 
                $tagihan->ttl_keamananipk + 
                $tagihan->ttl_kebersihan  + 
                $tagihan->ttl_airkotor    + 
                $tagihan->ttl_lain;
        $tagihan->ttl_tagihan = $total;

        //Realisasi
        $realisasi = 
                    $tagihan->rea_listrik     + 
                    $tagihan->rea_airbersih   + 
                    $tagihan->rea_keamananipk + 
                    $tagihan->rea_kebersihan  + 
                    $tagihan->rea_airkotor    + 
                    $tagihan->rea_lain;
        $tagihan->rea_tagihan = $realisasi;

        //Selisih
        $selisih =
                    $tagihan->sel_listrik     + 
                    $tagihan->sel_airbersih   + 
                    $tagihan->sel_keamananipk + 
                    $tagihan->sel_kebersihan  + 
                    $tagihan->sel_airkotor    + 
                    $tagihan->sel_lain;
        $tagihan->sel_tagihan = $selisih;

        $tagihan->save();
    }
}
