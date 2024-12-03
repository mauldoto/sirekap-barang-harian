<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class AlokasiExport implements FromView
{
    public $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function view(): View
    {
        return view('exports.excel.alokasi-devices', [
            'items' => $this->item
        ]);
    }
}
