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
        $newLokasi->kode = 'L' . rand(100, 999);

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
        $newSubLokasi = new SubLokasi();
        $newSubLokasi->nama = $request->nama_sublokasi;
        $newSubLokasi->deskripsi = $request->deskripsi_sublokasi;
        $newSubLokasi->kode = 'SL' . rand(100, 999);

        if (!$newSubLokasi->save()) {
            return back()->withErrors(['Sublokasi gagal tersimpan.']);
        }

        return back()->with(['success' => 'Sublokasi berhasil tersimpan.']);
    }

    public function subupdate(Request $request, $id)
    {
        $sublokasi = SubLokasi::where('id', $id)->first();
        if (!$sublokasi) {
            return back()->withErrors(['Sublokasi tidak ditemukan.']);
        }

        $sublokasi->nama = $request->nama_lokasi;
        $sublokasi->deskripsi = $request->deskripsi_lokasi;

        if (!$sublokasi->save()) {
            return back()->withErrors(['Sublokasi gagal terupdate.']);
        }

        return back()->with(['success' => 'Sublokasi berhasil terupdate.']);
    }

    public function subdelete(Request $request, $id)
    {
        $sublokasi = SubLokasi::where('id', $id)->first();
        if (!$sublokasi) {
            return back()->withErrors(['Sublokasi tidak ditemukan.']);
        }

        if (!$sublokasi->delete()) {
            return back()->withErrors(['Sublokasi gagal dihapus.']);
        }

        return back()->with(['success' => 'Sublokasi berhasil dihapus.']);
    }
}
