<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class AktivitasExport implements FromView
{
    public $aktivitas;

    public function __construct($aktivitas)
    {
        $this->aktivitas = $aktivitas;
    }

    public function view(): View
    {
        return view('exports.excel.aktivitas', [
            'aktivitas' => $this->aktivitas
        ]);
    }
}
