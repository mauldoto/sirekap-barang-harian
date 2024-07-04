<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class BarangImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        foreach ($rows as $row) 
        {
            Barang::create([
                'name' => $row[0],
            ]);
        }
    }
}