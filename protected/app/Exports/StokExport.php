<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;

class StokExport implements FromView
{
    public $stok;

    public function __construct($stok)
    {
        $this->stok = $stok;
    }

    public function view(): View
    {
        return view('exports.excel.stok-barang', [
            'stok' => $this->stok
        ]);
    }
}
