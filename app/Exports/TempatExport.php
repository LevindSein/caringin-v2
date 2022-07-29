<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use App\Models\TempatUsaha;

class TempatExport implements FromView, WithEvents
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(30);
                $event->sheet->getStyle('D')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('E')->getAlignment()->setWrapText(true);
            },
        ];
    }

    public function view(): View
    {
        $data = TempatUsaha::with('pengguna', 'pemilik')->orderBy('kd_kontrol', 'asc')->get();

        return view('tempatusaha.excel', [
            'data' => $data
        ]);
    }
}
