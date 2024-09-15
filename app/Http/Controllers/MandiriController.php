<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tagihan;

use Excel;

use App\Exports\MandiriExport;
use App\Models\Mandiri;
use Carbon\Carbon;

class MandiriController extends Controller
{
    public function index()
    {
        $data = Tagihan::selectRaw('sum(sel_tagihan) as bill, kd_kontrol')
        ->groupBy('kd_kontrol')
        ->where([['stt_lunas',0],['stt_publish',1],['sel_tagihan','>',0]])
        ->get();

        foreach ($data as $val) {
            $number = rand(11111111, 99999999);
            while(Mandiri::where('number', $number)->first()){
                $number = rand(11111111, 99999999);
            }

            $record = Mandiri::where('name', $val->kd_kontrol)->first();

            $periode   = 'Februari';
            $jth_tempo = 'Februari';
            $open      = '20230201';
            $close     = '20230215';
            $bill      = "01\Total\Total\\" . $val->bill;

            if($record){
                $record->periode   = $periode;
                $record->jth_tempo = $jth_tempo;
                $record->open      = $open;
                $record->close     = $close;
                $record->bill      = $bill;

                $record->save();
            } else {
                Mandiri::create([
                    'kode'      => '50213',
                    'number'    => $number,
                    'currency'  => 'IDR',
                    'name'      => $val->kd_kontrol,
                    'periode'   => $periode,
                    'jth_tempo' => $jth_tempo,
                    'open'      => $open,
                    'close'     => $close,
                    'bill'      => $bill
                ]);
            }
        }

        return Excel::download(new MandiriExport, 'Mandiri-' . Carbon::now() . '.xlsx');
    }
}
