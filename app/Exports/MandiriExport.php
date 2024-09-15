<?php

namespace App\Exports;

use App\Models\Mandiri;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MandiriExport implements FromView, WithColumnFormatting
{
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'F' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function view(): View
    {
        $dataset = Mandiri::get();

        return view('layanan.mandiri.generate', [
            'dataset' => $dataset
        ]);
    }
}
