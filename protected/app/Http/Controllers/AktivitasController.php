<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\AktivitasKaryawan;
use App\Models\Karyawan;
use App\Models\Lokasi;
use App\Models\SubLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AktivitasController extends Controller
{
    public function index()
    {
        return view('contents.aktivitas.index');
    }

    public function getSubLokasi(Request $request, $ids)
    {
        $sublokasi = SubLokasi::where('id_lokasi', $ids)->get();
        // if ($type != 'json') {
        //     if (!$sublokasi) {
        //         return back()->withErrors(['Barang tidak ditemukan']);
        //     }

        //     return; //maybe view
        // }

        if (!$sublokasi) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $sublokasi
        ]);
    }

    public function input()
    {
        $karyawan = Karyawan::all();
        $lokasi = Lokasi::all();
        return view('contents.aktivitas.input', compact('karyawan', 'lokasi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'nullable|string',
            'nama' => 'required|string',
            'satuan' => 'required|string',
            'deskripsi' => 'nullable',
        ]);
 
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        DB::beginTransaction();

        $newActivity = new Aktivitas();
        $newActivity->id_lokasi;
        $newActivity->id_sublokasi;
        $newActivity->tanggal_berangkat;
        $newActivity->tanggal_pulang;
        $newActivity->deskripsi = $request->deskripsi;

        foreach ($request->teknisi as $key => $teknisi) {
            $checkTeknisi = Karyawan::where('id', $teknisi)->first();
            if (!$checkTeknisi) {
                DB::rollBack();
                return back()->withErrors(['Input data teknisi ke aktivitas gagal, data teknisi tidak ditemukan.'])->withInput();
            }

            $inpTeknisi = new AktivitasKaryawan();
            $inpTeknisi->id_aktivitas = $newActivity->id;
            $inpTeknisi->id_karyawan = $checkTeknisi->id;

            if (!$inpTeknisi->save()) {
                DB::rollBack();
                return back()->withErrors(['Input data teknisi ke aktivitas gagal.'])->withInput();
            }
        }

        DB::commit();
        return back()->with(['success' => 'Input aktivitas berhasil.']);
    }
}
