<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PenggunaanBarangExport implements FromView
{
    public $data;
    public $period;

    public function __construct($data, $period)
    {
        $this->data = $data;
        $this->period = $period;
    }

    public function view(): View
    {
        return view('exports.excel.penggunaan-barang', [
            'data' => $this->data,
            'start'     => Carbon::createFromFormat('Y-m-d', $this->period[0])->format('d/m/Y'),
            'end'     => Carbon::createFromFormat('Y-m-d', $this->period[1])->format('d/m/Y'),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Mendapatkan style array untuk border
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        // Mengatur border untuk seluruh sheet
        $sheet->getStyle('A1:D100')->applyFromArray($styleArray); // Sesuaikan range sesuai kebutuhan
    }
}
