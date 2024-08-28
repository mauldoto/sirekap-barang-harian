<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class AktivitasKaryawanExport implements FromView
{
    public $aktivitas;
    public $period;

    public function __construct($aktivitas, $period)
    {
        $this->aktivitas = $aktivitas;
        $this->period = $period;
    }

    public function view(): View
    {
        return view('exports.excel.aktivitas-karyawan', [
            'aktivitas' => $this->aktivitas,
            'start'     => Carbon::createFromFormat('Y-m-d', $this->period[0])->format('d/m/Y'),
            'end'     => Carbon::createFromFormat('Y-m-d', $this->period[1])->format('d/m/Y'),
        ]);
    }
}
