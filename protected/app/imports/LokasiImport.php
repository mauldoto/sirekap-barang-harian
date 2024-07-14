<?php

namespace App\Imports;

use App\Models\Lokasi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class LokasiImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        foreach ($rows as $index => $row) {
            if ($index < 1) continue;

            $check = Lokasi::where('kode', $row[0])
                ->orWhere('nama', $row[1])
                ->first();

            $newLokasi = new Lokasi();
            if ($check) {
                $newLokasi = $check;
            }

            $newLokasi->kode = $row[0] ? $row[0] : generateReference('L');
            $newLokasi->nama = $row[1];
            $newLokasi->deskripsi = $row[2];
            $newLokasi->input_by = Auth::user()->id;

            $newLokasi->save();
        }

        DB::commit();
    }
}
