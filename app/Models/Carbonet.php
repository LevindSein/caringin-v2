<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;

class Carbonet extends Model
{
    use HasFactory;

    private $tanggal;
    private $tambah;

    public function __construct($tanggal = '', $tambah = 0)
    {
        $this -> tanggal = $tanggal;
        $this -> tambah  = $tambah;
    }

    public function __toString()
    {
        $date = new DateTime($this->tanggal);
        $time = strtotime($this->tanggal);
        $time = date("d",$time);
        $newDate = $date->add($this->add_months($this->tambah, $date));
        $dateReturned = $newDate->format('Y-m-'.$time); 

        return $dateReturned;
    }

    public function add_months($months, DateTime $dateObject) 
    {
        $next = new DateTime($dateObject->format('Y-m-d'));
        $next->modify('last day of +'.$months.' month');

        if($dateObject->format('d') > $next->format('d')) {
            return $dateObject->diff($next);
        } else {
            return new DateInterval('P'.$months.'M');
        }
    }
}
