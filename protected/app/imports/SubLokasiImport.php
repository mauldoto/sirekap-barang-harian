<?php

namespace App\Imports;

use App\Models\Lokasi;
use App\Models\SubLokasi;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubLokasiImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        foreach ($rows as $index => $row) {
            if ($index < 1) continue;

            $check = SubLokasi::where('kode', $row[0])
                ->orWhere('nama', $row[1])
                ->first();

            $newSubLokasi = new SubLokasi();
            if ($check) {
                $newSubLokasi = $check;
            }

            $lokasi = Lokasi::where('nama', $row[1])->first();
            if (!$lokasi) {
                throw new Exception("Lokasi '" . $row[1] . "' tidak ditemukan", 1);
            }

            $newSubLokasi->id_lokasi = $lokasi->id;
            $newSubLokasi->kode = $row[0] ? $row[0] : generateReference('SL');
            $newSubLokasi->nama = $row[2];
            $newSubLokasi->deskripsi = $row[3];
            $newSubLokasi->input_by = Auth::user()->id;

            $newSubLokasi->save();
        }

        DB::commit();
    }
}
