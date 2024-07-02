<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Lokasi;
use App\Models\SubLokasi;
use Illuminate\Http\Request;

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

    public function store()
    {
        
    }
}
