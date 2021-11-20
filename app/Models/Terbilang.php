<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terbilang extends Model
{
    use HasFactory;

    public function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = self::penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = self::penyebut($nilai/10)." puluh". self::penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . self::penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = self::penyebut($nilai/100) . " ratus" . self::penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . self::penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = self::penyebut($nilai/1000) . " ribu" . self::penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = self::penyebut($nilai/1000000) . " juta" . self::penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = self::penyebut($nilai/1000000000) . " miliar" . self::penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = self::penyebut($nilai/1000000000000) . " triliun" . self::penyebut(fmod($nilai,1000000000000));
		}
		return $temp;
	}

	public static function convert($nilai) {
        $penyebut = new Terbilang;
		if($nilai < 0) {
			$hasil = "minus ". trim($penyebut->penyebut($nilai)). " rupiah";
		} else {
			$hasil = trim($penyebut->penyebut($nilai)). " rupiah";
		}
		return $hasil;
	}

}
