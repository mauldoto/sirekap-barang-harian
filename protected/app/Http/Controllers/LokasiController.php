<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\SubLokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();
        $sublokasi = SubLokasi::all();
        return view('contents.lokasi.index', compact('lokasi', 'sublokasi'));
    }

    public function store(Request $request)
    {
        $newLokasi = new Lokasi();
        $newLokasi->nama = $request->nama_lokasi;
        $newLokasi->deskripsi = $request->deskripsi_lokasi;
        $newLokasi->kode = rand(100, 999);

        if (!$newLokasi->save()) {
            return back()->withErrors(['Lokasi gagal tersimpan.']);
        }

        return back()->with(['success' => 'Lokasi berhasil tersimpan.']);
    }

    public function update(Request $request, $id)
    {
        $lokasi = Lokasi::where('id', $id)->first();
        if (!$lokasi) {
            return back()->withErrors(['Lokasi tidak ditemukan.']);
        }

        $lokasi->nama = $request->nama_lokasi;
        $lokasi->deskripsi = $request->deskripsi_lokasi;
        $lokasi->kode = rand(100, 999);

        if (!$lokasi->save()) {
            return back()->withErrors(['Lokasi gagal terupdate.']);
        }

        return back()->with(['success' => 'Lokasi berhasil terupdate.']);
    }

    public function delete(Request $request, $id)
    {
        $lokasi = Lokasi::where('id', $id)->first();
        if (!$lokasi) {
            return back()->withErrors(['Lokasi tidak ditemukan.']);
        }

        if (!$lokasi->delete()) {
            return back()->withErrors(['Lokasi gagal dihapus.']);
        }

        return back()->with(['success' => 'Lokasi berhasil dihapus.']);
    }

    public function substore(Request $request)
    {
    }

    public function subupdate(Request $request, $id)
    {
    }

    public function subdelete(Request $request, $id)
    {
    }
}
