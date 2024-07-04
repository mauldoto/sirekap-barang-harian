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

        foreach ($rows as $index => $row) 
        {
            if ($index <= 6) {
                continue;
            }

            $check = Barang::where('kode', $row[2])->first();
            $newBarang = new Barang();
            if ($check) {
                $newBarang = $check;
            }

            $newBarang->kode = $row[2] ? $row[2] : generateReference('B');
            $newBarang->nama = $row[3];
            $newBarang->deskripsi = $row[5];
            $newBarang->satuan = $row[4];

            $newBarang->save();
        }

        DB::commit();
    }
}