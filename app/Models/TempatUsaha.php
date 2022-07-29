<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\AlatListrik;
use App\Models\AlatAir;

use App\Models\TarifKeamananIpk;
use App\Models\TarifKebersihan;
use App\Models\TarifAirKotor;
use App\Models\TarifLain;

class TempatUsaha extends Model
{
    use HasFactory;
    protected $table ='tempat_usaha';
    protected $fillable = [
        'id',
        'trf_kebersihan',
        'trf_keamananipk',
        'trf_listrik',
        'trf_airbersih',
        'trf_airkotor',
        'trf_lain',
        'id_pengguna',
        'id_pemilik',
        'kd_kontrol',
        'no_alamat',
        'jml_alamat',
        'bentuk_usaha',
        'id_meteran_air',
        'id_meteran_listrik',
        'blok',
        'daya',
        'dis_airbersih',
        'dis_listrik',
        'dis_keamananipk',
        'dis_kebersihan',
        'stt_cicil',
        'stt_tempat',
        'ket_tempat',
        'lok_tempat',
        'stt_bongkar',
        'updated_at',
        'created_at'
    ];

    public static function rekap(){
        return TempatUsaha::
            select(
                'blok',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN stt_tempat = '1' THEN 1 ELSE 0 END) as aktif"),
                )
            ->groupBy('blok')
            ->get();
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna')->withDefault([
            'id' => null,
            'name' => '-'
        ]);
    }

    public function pemilik(){
        return $this->belongsTo(User::class, 'id_pemilik')->withDefault([
            'id' => null,
            'name' => '-'
        ]);
    }

    public static function status($val){
        switch ($val) {
            case 3:
                return 'Bebas Bayar';
                break;
            case 1:
                return 'Aktif';
                break;
            default:
                return 'Nonaktif';
                break;
        }
    }

    public static function fasilitas($fas){
        if($fas == 'diskon'){
            return TempatUsaha::where('tempat_usaha.dis_airbersih','!=',NULL)
            ->orWhere('tempat_usaha.dis_listrik','!=',NULL)
            ->orWhere('tempat_usaha.dis_keamananipk','>',0)
            ->orWhere('tempat_usaha.dis_kebersihan','>',0)
            ->leftJoin('user as user_pengguna','tempat_usaha.id_pengguna','=','user_pengguna.id')
            ->leftJoin('user as user_pemilik','tempat_usaha.id_pemilik','=','user_pemilik.id')
            ->select(
                'tempat_usaha.id',
                'user_pengguna.nama as pengguna',
                'user_pemilik.nama as pemilik',
                'tempat_usaha.kd_kontrol',
                'tempat_usaha.lok_tempat',
                'tempat_usaha.no_alamat',
                'tempat_usaha.jml_alamat',
                'tempat_usaha.bentuk_usaha',
                'tempat_usaha.dis_listrik',
                'tempat_usaha.dis_airbersih',
                'tempat_usaha.dis_keamananipk',
                'tempat_usaha.dis_kebersihan',
                'tempat_usaha.stt_cicil',
                'tempat_usaha.stt_tempat',
                'tempat_usaha.ket_tempat',
                )
            ->get();
        }
        else{
            return TempatUsaha::where('tempat_usaha.trf_'.$fas,'!=',NULL)
            ->leftJoin('user as user_pengguna','tempat_usaha.id_pengguna','=','user_pengguna.id')
            ->leftJoin('user as user_pemilik','tempat_usaha.id_pemilik','=','user_pemilik.id')
            ->select(
                'tempat_usaha.id',
                'user_pengguna.nama as pengguna',
                'user_pemilik.nama as pemilik',
                'tempat_usaha.kd_kontrol',
                'tempat_usaha.lok_tempat',
                'tempat_usaha.no_alamat',
                'tempat_usaha.jml_alamat',
                'tempat_usaha.bentuk_usaha',
                'tempat_usaha.stt_cicil',
                'tempat_usaha.stt_tempat',
                'tempat_usaha.ket_tempat',
                )
            ->get();
        }
    }

    public static function airAvailable(){
        return DB::table('meteran_air')->where('stt_sedia',0)->get();
    }

    public static function listrikAvailable(){
        return DB::table('meteran_listrik')->where('stt_sedia',0)->get();
    }

    public static function trfKeamananIpk(){
        return DB::table('trf_keamanan_ipk')->orderBy('tarif','asc')->get();
    }

    public static function trfKebersihan(){
        return DB::table('trf_kebersihan')->orderBy('tarif','asc')->get();
    }

    public static function trfAirKotor(){
        return DB::table('trf_air_kotor')->orderBy('tarif','asc')->get();
    }

    public static function trfLain(){
        return DB::table('trf_lain')->orderBy('tarif','asc')->get();
    }

    public static function kode($blok,$los){
        $kode = "";
        if(is_numeric($los[0]) == TRUE){
            if($los[0] < 10){
                $kode = $blok."-"."00".$los[0];
            }
            else if($los[0] < 100){
                $kode = $blok."-"."0".$los[0];
            }
            else{
                $kode = $blok."-".$los[0];
            }
        }
        else{
            $num = 0;
            $strnum = 0;
            for($i=0; $i < strlen($los[0]); $i++){
                if (is_numeric($los[0][$i]) == TRUE){
                    $num++;
                }
                else{
                    $strnum = 1;
                    break;
                }
            }

            if($num == 1){
                $kode = $blok."-"."00".$los[0];
            }
            else if($num == 2){
                $kode = $blok."-"."0".$los[0];
            }
            else if($num >= 3 || $strnum == 1){
                $kode = $blok."-".$los[0];
            }
        }
        return $kode;
    }
}
