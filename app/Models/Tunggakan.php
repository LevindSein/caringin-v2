<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Tagihan;

class Tunggakan extends Model
{
    use HasFactory;

    public static function data(){
        return Tagihan::select('bln_tagihan')
        ->groupBy('bln_tagihan')
        ->orderBy('bln_tagihan','desc')
        ->get();
    }

    public static function rekapListrik($bulan){
        return Tagihan::
            where([
                    ['tagihan.bln_tagihan',$bulan],
                    ['tagihan.stt_listrik',1]
                ])
            ->select(
                    'tagihan.blok',
                    DB::raw('SUM(tagihan.daya_listrik) as daya'),
                    DB::raw('SUM(tagihan.pakai_listrik) as pakai'),
                    DB::raw('SUM(tagihan.beban_listrik) as beban'),
                    DB::raw('SUM(tagihan.bpju_listrik) as bpju'),
                    DB::raw('SUM(tagihan.sel_listrik) as tagihan'),
                    DB::raw('COUNT(*) as pengguna')
                )
            ->groupBy('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->get();
    }

    public static function ttlRekapListrik($data){
        $total = array();
        $pengguna = 0;
        $daya = 0;
        $pakai = 0;
        $beban = 0;
        $bpju = 0;
        $tagihan = 0;
        foreach($data as $d){
            $pengguna = $pengguna + $d->pengguna;
            $daya = $daya + $d->daya;
            $pakai = $pakai + $d->pakai;
            $beban = $beban + $d->beban;
            $bpju = $bpju + $d->bpju;
            $tagihan = $tagihan + $d->tagihan;
        }
        $total[0] = $pengguna;
        $total[1] = $daya;
        $total[2] = $pakai;
        $total[3] = $beban;
        $total[4] = $bpju;
        $total[5] = $tagihan;
        return $total;
    }

    public static function rincianListrik($bulan){
        $blok = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_listrik',1]
            ])
            ->select('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->groupBy('tagihan.blok')
            ->get();

        $dataset = array();
        for($i=0; $i<count($blok); $i++){
            $dataset[$i][0] = $blok[$i]->blok;
            $dataset[$i][1] = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_listrik',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                'tagihan.kd_kontrol as kontrol',
                'tagihan.nama as pengguna',
                'tagihan.daya_listrik as daya',
                'tagihan.awal_listrik as lalu',
                'tagihan.akhir_listrik as baru',
                'tagihan.pakai_listrik as pakai',
                'tagihan.rekmin_listrik as rekmin',
                'tagihan.blok1_listrik as blok1',
                'tagihan.blok2_listrik as blok2',
                'tagihan.beban_listrik as beban',
                'tagihan.bpju_listrik as bpju',
                'tagihan.sel_listrik as tagihan',
                'tagihan.lok_tempat as lokasi'
                )
            ->orderBy('tagihan.kd_kontrol','asc')
            ->get();

            $dataset[$i][2] = Tagihan::where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_listrik',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                DB::raw('SUM(tagihan.daya_listrik) as daya'),
                DB::raw('SUM(tagihan.awal_listrik) as lalu'),
                DB::raw('SUM(tagihan.akhir_listrik) as baru'),
                DB::raw('SUM(tagihan.pakai_listrik) as pakai'),
                DB::raw('SUM(tagihan.rekmin_listrik) as rekmin'),
                DB::raw('SUM(tagihan.blok1_listrik) as blok1'),
                DB::raw('SUM(tagihan.blok2_listrik) as blok2'),
                DB::raw('SUM(tagihan.beban_listrik) as beban'),
                DB::raw('SUM(tagihan.bpju_listrik) as bpju'),
                DB::raw('SUM(tagihan.sel_listrik) as tagihan')
                )
            ->get();
        }
        return $dataset;
    }

    public static function rekapAirBersih($bulan){
        return Tagihan::
            where([
                    ['tagihan.bln_tagihan',$bulan],
                    ['tagihan.stt_airbersih',1]
                ])
            ->select(
                    'tagihan.blok',
                    DB::raw('SUM(tagihan.pakai_airbersih) as pakai'),
                    DB::raw('SUM(tagihan.beban_airbersih) as beban'),
                    DB::raw('SUM(tagihan.pemeliharaan_airbersih) as pemeliharaan'),
                    DB::raw('SUM(tagihan.arkot_airbersih) as arkot'),
                    DB::raw('SUM(tagihan.sel_airbersih) as tagihan'),
                    DB::raw('COUNT(*) as pengguna')
                )
            ->groupBy('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->get();
    }

    public static function ttlRekapAirBersih($data){
        $total = array();
        $pengguna = 0;
        $pakai = 0;
        $beban = 0;
        $pemeliharaan = 0;
        $arkot = 0;
        $tagihan = 0;
        foreach($data as $d){
            $pengguna = $pengguna + $d->pengguna;
            $pakai = $pakai + $d->pakai;
            $beban = $beban + $d->beban;
            $pemeliharaan = $pemeliharaan + $d->pemeliharaan;
            $arkot = $arkot + $d->arkot;
            $tagihan = $tagihan + $d->tagihan;
        }
        $total[0] = $pengguna;
        $total[1] = $pakai;
        $total[2] = $beban;
        $total[3] = $pemeliharaan;
        $total[4] = $arkot;
        $total[5] = $tagihan;
        return $total;
    }

    public static function rincianAirBersih($bulan){
        $blok = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_airbersih',1],
            ])
            ->select('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->groupBy('tagihan.blok')
            ->get();

        $dataset = array();
        for($i=0; $i<count($blok); $i++){
            $dataset[$i][0] = $blok[$i]->blok;
            $dataset[$i][1] = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_airbersih',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                'tagihan.kd_kontrol as kontrol',
                'tagihan.nama as pengguna',
                'tagihan.awal_airbersih as lalu',
                'tagihan.akhir_airbersih as baru',
                'tagihan.pakai_airbersih as pakai',
                'tagihan.byr_airbersih as bPakai',
                'tagihan.beban_airbersih as beban',
                'tagihan.pemeliharaan_airbersih as pemeliharaan',
                'tagihan.arkot_airbersih as arkot',
                'tagihan.sel_airbersih as tagihan',
                'tagihan.lok_tempat as lokasi'
                )
            ->orderBy('tagihan.kd_kontrol','asc')
            ->get();
            
            $dataset[$i][2] = Tagihan::where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_airbersih',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                DB::raw('SUM(tagihan.awal_airbersih) as lalu'),
                DB::raw('SUM(tagihan.akhir_airbersih) as baru'),
                DB::raw('SUM(tagihan.pakai_airbersih) as pakai'),
                DB::raw('SUM(tagihan.byr_airbersih) as bPakai'),
                DB::raw('SUM(tagihan.beban_airbersih) as beban'),
                DB::raw('SUM(tagihan.pemeliharaan_airbersih) as pemeliharaan'),
                DB::raw('SUM(tagihan.arkot_airbersih) as arkot'),
                DB::raw('SUM(tagihan.sel_airbersih) as tagihan')
                )
            ->get();
        }
        return $dataset;
    }

    public static function rekapKeamananIpk($bulan){
        return Tagihan::
            where([
                    ['tagihan.bln_tagihan',$bulan],
                    ['tagihan.stt_keamananipk',1]
                ])
            ->select(
                    'tagihan.blok',
                    DB::raw('SUM(tagihan.jml_alamat) as pengguna'),
                    DB::raw('SUM(tagihan.sub_keamananipk) as subtotal'),
                    DB::raw('SUM(tagihan.dis_keamananipk) as diskon'),
                    DB::raw('SUM(tagihan.sel_keamananipk) as tagihan'),
                )
            ->groupBy('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->get();
    }

    public static function ttlRekapKeamananIpk($data){
        $total = array();
        $pengguna = 0;
        $subtotal = 0;
        $diskon = 0;
        $tagihan = 0;
        foreach($data as $d){
            $pengguna = $pengguna + $d->pengguna;
            $subtotal = $subtotal + $d->subtotal;
            $diskon = $diskon + $d->diskon;
            $tagihan = $tagihan + $d->tagihan;
        }
        $total[0] = $pengguna;
        $total[1] = $subtotal;
        $total[2] = $diskon;
        $total[3] = $tagihan;
        return $total;
    }

    public static function rincianKeamananIpk($bulan){
        $blok = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_keamananipk',1],
            ])
            ->select('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->groupBy('tagihan.blok')
            ->get();

        $dataset = array();
        for($i=0; $i<count($blok); $i++){
            $dataset[$i][0] = $blok[$i]->blok;
            $dataset[$i][1] = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_keamananipk',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                'tagihan.kd_kontrol as kontrol',
                'tagihan.nama as pengguna',
                'tagihan.no_alamat as nomor',
                'tagihan.lok_tempat as lokasi',
                'tagihan.jml_alamat as jumlah',
                'tagihan.sub_keamananipk as subtotal',
                'tagihan.dis_keamananipk as diskon',
                'tagihan.sel_keamananipk as tagihan'
                )
            ->orderBy('tagihan.kd_kontrol','asc')
            ->get();
            
            $dataset[$i][2] = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_keamananipk',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                DB::raw('SUM(tagihan.jml_alamat) as jumlah'),
                DB::raw('SUM(tagihan.sub_keamananipk) as subtotal'),
                DB::raw('SUM(tagihan.dis_keamananipk) as diskon'),
                DB::raw('SUM(tagihan.sel_keamananipk) as tagihan')
                )
            ->get();
        }
        return $dataset;
    }

    public static function rekapKebersihan($bulan){
        return Tagihan::
            where([
                    ['tagihan.bln_tagihan',$bulan],
                    ['tagihan.stt_kebersihan',1]
                ])
            ->select(
                    'tagihan.blok',
                    DB::raw('SUM(tagihan.jml_alamat) as pengguna'),
                    DB::raw('SUM(tagihan.sub_kebersihan) as subtotal'),
                    DB::raw('SUM(tagihan.dis_kebersihan) as diskon'),
                    DB::raw('SUM(tagihan.sel_kebersihan) as tagihan'),
                )
            ->groupBy('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->get();
    }

    public static function ttlRekapKebersihan($data){
        $total = array();
        $pengguna = 0;
        $subtotal = 0;
        $diskon = 0;
        $tagihan = 0;
        foreach($data as $d){
            $pengguna = $pengguna + $d->pengguna;
            $subtotal = $subtotal + $d->subtotal;
            $diskon = $diskon + $d->diskon;
            $tagihan = $tagihan + $d->tagihan;
        }
        $total[0] = $pengguna;
        $total[1] = $subtotal;
        $total[2] = $diskon;
        $total[3] = $tagihan;
        return $total;
    }

    public static function rincianKebersihan($bulan){
        $blok = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_kebersihan',1],
            ])
            ->select('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->groupBy('tagihan.blok')
            ->get();

        $dataset = array();
        for($i=0; $i<count($blok); $i++){
            $dataset[$i][0] = $blok[$i]->blok;
            $dataset[$i][1] = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_kebersihan',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                'tagihan.kd_kontrol as kontrol',
                'tagihan.nama as pengguna',
                'tagihan.no_alamat as nomor',
                'tagihan.lok_tempat as lokasi',
                'tagihan.jml_alamat as jumlah',
                'tagihan.sub_kebersihan as subtotal',
                'tagihan.dis_kebersihan as diskon',
                'tagihan.sel_kebersihan as tagihan'
                )
            ->orderBy('tagihan.kd_kontrol','asc')
            ->get();
            
            $dataset[$i][2] = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.stt_kebersihan',1],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                DB::raw('SUM(tagihan.jml_alamat) as jumlah'),
                DB::raw('SUM(tagihan.sub_kebersihan) as subtotal'),
                DB::raw('SUM(tagihan.dis_kebersihan) as diskon'),
                DB::raw('SUM(tagihan.sel_kebersihan) as tagihan')
                )
            ->get();
        }
        return $dataset;
    }

    public static function rekapTotal($bulan){
        return Tagihan::
            where('tagihan.bln_tagihan',$bulan)
            ->select(
                    'tagihan.blok',
                    DB::raw('SUM(tagihan.sel_listrik) as listrik'),
                    DB::raw('SUM(tagihan.sel_airbersih) as airbersih'),
                    DB::raw('SUM(tagihan.sel_keamananipk) as keamananipk'),
                    DB::raw('SUM(tagihan.sel_kebersihan) as kebersihan'),
                    DB::raw('SUM(tagihan.sel_airkotor) as airkotor'),
                    DB::raw('SUM(tagihan.sel_lain) as lain'),
                    DB::raw('SUM(tagihan.sel_tagihan) as tagihan'),
                    DB::raw('COUNT(*) as pengguna')
                )
            ->groupBy('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->get();
    }

    public static function ttlRekapTotal($data){
        $total = array();
        $pengguna = 0;
        $listrik = 0;
        $airbersih = 0;
        $keamananipk = 0;
        $kebersihan = 0;
        $airkotor = 0;
        $lain = 0;
        $tagihan = 0;
        foreach($data as $d){
            $pengguna = $pengguna + $d->pengguna;
            $listrik = $listrik + $d->listrik;
            $airbersih = $airbersih + $d->airbersih;
            $keamananipk = $keamananipk + $d->keamananipk;
            $kebersihan = $kebersihan + $d->kebersihan;
            $airkotor = $airkotor + $d->airkotor;
            $lain = $lain + $d->lain;
            $tagihan = $tagihan + $d->tagihan;
        }
        $total['pengguna'] = $pengguna;
        $total['listrik'] = $listrik;
        $total['airbersih'] = $airbersih;
        $total['keamananipk'] = $keamananipk;
        $total['kebersihan'] = $kebersihan;
        $total['airkotor'] = $airkotor;
        $total['lain'] = $lain;
        $total['tagihan'] = $tagihan;
        return $total;
    }

    public static function rincianTotal($bulan){
        $blok = Tagihan::where('tagihan.bln_tagihan',$bulan)
            ->select('tagihan.blok')
            ->orderBy('tagihan.blok','asc')
            ->groupBy('tagihan.blok')
            ->get();

        $dataset = array();
        for($i=0; $i<count($blok); $i++){
            $dataset[$i][0] = $blok[$i]->blok;
            $dataset[$i][1] = Tagihan::
            where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                'tagihan.kd_kontrol as kontrol',
                'tagihan.nama as pengguna',
                'tagihan.no_alamat as nomor',
                'tagihan.lok_tempat as lokasi',
                'tagihan.jml_alamat as alamat',
                'tagihan.sel_listrik as listrik',
                'tagihan.sel_airbersih as airbersih',
                'tagihan.sel_keamananipk as keamananipk',
                'tagihan.sel_kebersihan as kebersihan',
                'tagihan.sel_airkotor as airkotor',
                'tagihan.sel_lain as lain',
                'tagihan.sel_tagihan as tagihan',
                )
            ->orderBy('tagihan.kd_kontrol','asc')
            ->get();

            $dataset[$i][2] = Tagihan::where([
                ['tagihan.bln_tagihan',$bulan],
                ['tagihan.blok',$blok[$i]->blok]
            ])
            ->select(
                DB::raw('SUM(tagihan.jml_alamat) as alamat'),
                DB::raw('SUM(tagihan.sel_listrik) as listrik'),
                DB::raw('SUM(tagihan.sel_airbersih) as airbersih'),
                DB::raw('SUM(tagihan.sel_keamananipk) as keamananipk'),
                DB::raw('SUM(tagihan.sel_kebersihan) as kebersihan'),
                DB::raw('SUM(tagihan.sel_airkotor) as airkotor'),
                DB::raw('SUM(tagihan.sel_lain) as lain'),
                DB::raw('SUM(tagihan.sel_tagihan) as tagihan')
                )
            ->get();
        }
        return $dataset;
    }
}
