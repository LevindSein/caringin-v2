<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struk80mm extends Model
{
    use HasFactory;
    private $fasilitas;
    private $harga;
    private $status;
    private $ket;

    public function __construct($fasilitas = '', $harga = '', $status = true, $ket = false)
    {
        $this->fasilitas = $fasilitas;
        $this->harga     = $harga;
        $this->status    = $status;
        $this->ket       = $ket;
    }

    public function __toString()
    {
        if($this->status){
            if($this->ket){
                $left = str_pad($this->fasilitas, 6);
                $right = str_pad($this->harga, 13, ' ', STR_PAD_LEFT);
            }
            else{
                $left = str_pad($this->fasilitas, 20);
                $right = str_pad($this->harga, 20, ' ', STR_PAD_LEFT);
            }
            return "$left$right\n";
        }
        else{
            $left = str_pad('(', 14, ' ', STR_PAD_LEFT).str_pad($this->fasilitas, 6);
            $right = str_pad($this->harga, 10, ' ', STR_PAD_LEFT).')';
            return "$left$right\n";
        }
    }
}
