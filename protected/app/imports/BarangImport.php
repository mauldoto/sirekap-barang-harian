<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class BarangImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        foreach ($rows as $index => $row) {
            if ($index < 1) continue;
            
            $check = Barang::where('kode', $row[0])
                ->orWhere('nama', $row[1])
                ->first();

            $newBarang = new Barang();
            if ($check) {
                $newBarang = $check;
            }

            $newBarang->kode = $row[0] ? $row[0] : generateReference('B');
            $newBarang->nama = $row[1];
            $newBarang->deskripsi = $row[3];
            $newBarang->satuan = $row[2];
            $newBarang->input_by = Auth::user()->id;

            $newBarang->save();
        }

        DB::commit();
    }
}
