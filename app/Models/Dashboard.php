<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Tagihan;
use App\Models\TempatUsaha;
use App\Models\Pembayaran;

class Dashboard extends Model
{
    use HasFactory;

    public static function rincian($thn){
        $listrik = array();
        $airbersih = array();
        $keamananipk = array();
        $kebersihan = array();
        $j = 0;

        for($i=1; $i<=12; $i++){
            if($i < 10){
                $data = Tagihan::where('bln_tagihan',($thn."-0".$i))
                ->select(
                    DB::raw('SUM(ttl_listrik) as listrik'),
                    DB::raw('SUM(ttl_airbersih) as airbersih'),
                    DB::raw('SUM(ttl_keamananipk) as keamananipk'),
                    DB::raw('SUM(ttl_kebersihan) as kebersihan'))
                ->get();
            }
            else{
                $data = Tagihan::where('bln_tagihan',($thn."-".$i))
                ->select(
                    DB::raw('SUM(ttl_listrik) as listrik'),
                    DB::raw('SUM(ttl_airbersih) as airbersih'),
                    DB::raw('SUM(ttl_keamananipk) as keamananipk'),
                    DB::raw('SUM(ttl_kebersihan) as kebersihan'))
                ->get();
            }

            if($data[0]->listrik == NULL){
                $ttl_listrik = 0;
            }
            else{
                $ttl_listrik = $data[0]->listrik;
            }

            if($data[0]->airbersih == NULL){
                $ttl_airbersih = 0;
            }
            else{
                $ttl_airbersih = $data[0]->airbersih;
            }

            if($data[0]->keamananipk == NULL){
                $ttl_keamananipk = 0;
            }
            else{
                $ttl_keamananipk = $data[0]->keamananipk;
            }

            if($data[0]->kebersihan == NULL){
                $ttl_kebersihan = 0;
            }
            else{
                $ttl_kebersihan = $data[0]->kebersihan;
            }

            $listrik[$j] = $ttl_listrik;
            $airbersih[$j] = $ttl_airbersih;
            $keamananipk[$j] = $ttl_keamananipk;
            $kebersihan[$j] = $ttl_kebersihan;
            $j++;
        }

        return array($listrik,$airbersih,$keamananipk,$kebersihan);
    }

    public static function pendapatan($thn){
        $tagihan = array();
        $realisasi = array();
        $selisih = array();
        $j=0;
        for($i=1; $i<=12; $i++){
            if($i < 10){
                $data = Tagihan::where('bln_tagihan',($thn."-0".$i))
                ->select(
                    DB::raw('SUM(ttl_tagihan) as tagihan'),
                    DB::raw('SUM(rea_tagihan) as realisasi'),
                    DB::raw('SUM(sel_tagihan) as selisih'))
                ->get();
            }
            else{
                $data = Tagihan::where('bln_tagihan',($thn."-".$i))
                ->select(
                    DB::raw('SUM(ttl_tagihan) as tagihan'),
                    DB::raw('SUM(rea_tagihan) as realisasi'),
                    DB::raw('SUM(sel_tagihan) as selisih'))
                ->get();
            }

            if($data[0]->tagihan == NULL){
                $ttl_tagihan = 0;
            }
            else{
                $ttl_tagihan = $data[0]->tagihan;
            }

            if($data[0]->realisasi == NULL){
                $ttl_realisasi = 0;
            }
            else{
                $ttl_realisasi = $data[0]->realisasi;
            }

            if($data[0]->selisih == NULL){
                $ttl_selisih = 0;
            }
            else{
                $ttl_selisih = $data[0]->selisih;
            }

            $tagihan[$j] = $ttl_tagihan;
            $realisasi[$j] = $ttl_realisasi;
            $selisih[$j] = $ttl_selisih;
            $j++;
        }

        return array($tagihan,$realisasi,$selisih);
    }

    public static function akumulasi($thn){
        $tagihanAku = array();
        $realisasiAku = array();
        $selisihAku = array();
        $ttl_tagihan = 0;
        $ttl_realisasi = 0;
        $ttl_selisih = 0;
        $j=0;
        for($i=1; $i<=12; $i++){
            if($i < 10){
                $data = Tagihan::where('bln_tagihan',($thn."-0".$i))
                ->select(
                    DB::raw('SUM(ttl_tagihan) as tagihan'),
                    DB::raw('SUM(rea_tagihan) as realisasi'),
                    DB::raw('SUM(sel_tagihan) as selisih'))
                ->get();
            }
            else{
                $data = Tagihan::where('bln_tagihan',($thn."-".$i))
                ->select(
                    DB::raw('SUM(ttl_tagihan) as tagihan'),
                    DB::raw('SUM(rea_tagihan) as realisasi'),
                    DB::raw('SUM(sel_tagihan) as selisih'))
                ->get();
            }

            if($data[0]->tagihan == NULL){
                $ttl_tagihan = $ttl_tagihan + 0;
            }
            else{
                $ttl_tagihan = $ttl_tagihan + $data[0]->tagihan;
            }

            if($data[0]->realisasi == NULL){
                $ttl_realisasi = $ttl_realisasi + 0;
            }
            else{
                $ttl_realisasi = $ttl_realisasi + $data[0]->realisasi;
            }

            if($data[0]->selisih == NULL){
                $ttl_selisih = $ttl_selisih + 0;
            }
            else{
                $ttl_selisih = $ttl_selisih + $data[0]->selisih;
            }

            $tagihanAku[$j] = $ttl_tagihan;
            $realisasiAku[$j] = $ttl_realisasi;
            $selisihAku[$j] = $ttl_selisih;
            $j++;
        }

        return array($tagihanAku,$realisasiAku,$selisihAku);
    }

    public static function reaAirBersih($thn){
        $realisasi = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(rea_airbersih) as realisasi'))
        ->get();

        return $realisasi[0]->realisasi;
    }

    public static function reaListrik($thn){
        $realisasi = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(rea_listrik) as realisasi'))
        ->get();

        return $realisasi[0]->realisasi;
    }

    public static function reaKeamananIpk($thn){
        $realisasi = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(rea_keamananipk) as realisasi'))
        ->get();

        return $realisasi[0]->realisasi;
    }

    public static function reaKebersihan($thn){
        $realisasi = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(rea_kebersihan) as realisasi'))
        ->get();

        return $realisasi[0]->realisasi;
    }

    public static function selAirBersih($thn){
        $selisih = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(sel_airbersih) as selisih'))
        ->get();

        return $selisih[0]->selisih;
    }

    public static function selListrik($thn){
        $selisih = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(sel_listrik) as selisih'))
        ->get();

        return $selisih[0]->selisih;
    }

    public static function selKeamananIpk($thn){
        $selisih = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(sel_keamananipk) as selisih'))
        ->get();

        return $selisih[0]->selisih;
    }

    public static function selKebersihan($thn){
        $selisih = Tagihan::where('thn_tagihan',$thn)
        ->select(DB::raw('SUM(sel_kebersihan) as selisih'))
        ->get();

        return $selisih[0]->selisih;
    }
}
