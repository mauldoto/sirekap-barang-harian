<?php

namespace App\Imports;

use App\Models\Karyawan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class KaryawanImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        foreach ($rows as $index => $row) {
            if ($index < 1) continue;

            $check = Karyawan::where('kode', $row[0])
                ->orWhere('nama', $row[1])
                ->first();

            $newKaryawan = new Karyawan();
            if ($check) {
                $newKaryawan = $check;
            }

            $newKaryawan->kode = $row[0] ? $row[0] : generateReference('K');
            $newKaryawan->nama = $row[1];
            $newKaryawan->deskripsi = $row[3];
            $newKaryawan->input_by = Auth::user()->id;

            $newKaryawan->save();
        }

        DB::commit();
    }
}
