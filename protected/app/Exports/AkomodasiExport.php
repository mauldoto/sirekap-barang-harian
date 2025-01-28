<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class AkomodasiExport implements FromView
{
    public $item;
    public $period;

    public function __construct($item, $period)
    {
        $this->item = $item;
        $this->period = $period;
    }

    public function view(): View
    {
        return view('exports.excel.akomodasi', [
            'akomodasi' => $this->item,
            'start'     => Carbon::createFromFormat('Y-m-d', $this->period[0])->format('d/m/Y'),
            'end'       => Carbon::createFromFormat('Y-m-d', $this->period[1])->format('d/m/Y'),
        ]);
    }
}
