<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::all();
        return view('contents.barang.index', compact('barang'));
    }

    public function detail(Request $request, $id)
    {
        $type = $request->type;
        $barang = Barang::where('id', $id)->first();
        // if ($type != 'json') {
        //     if (!$barang) {
        //         return back()->withErrors(['Barang tidak ditemukan']);
        //     }

        //     return; //maybe view
        // }

        if (!$barang) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $barang
        ]);
    }

    public function store(Request $request)
    {
        $newBarang = new Barang();
        $newBarang->kode = $request->kode;
        $newBarang->nama = $request->nama;
        $newBarang->deskripsi = $request->deskripsi;
        $newBarang->satuan = $request->satuan;

        if (!$newBarang->save()) {
            return back()->withErrors(['Barang gagal tersimpan.'])->withInput();
        }

        return back()->with(['success' => 'Barang berhasil tersimpan.']);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::where('id', $id)->first();
        if (!$barang) {
            return back()->withErrors(['Barang tidak ditemukan.']);
        }

        $barang->nama = $request->nama;
        $barang->deskripsi = $request->deskripsi;
        $barang->satuan = $request->satuan;

        if (!$barang->save()) {
            return back()->withErrors(['Barang gagal terupdate.'])->withInput();
        }

        return back()->with(['success' => 'Barang berhasil terupdate.']);
    }

    public function delete(Request $request, $id)
    {
        $barang = Barang::where('id', $id)->first();
        if (!$barang) {
            return back()->withErrors(['Barang tidak ditemukan.']);
        }

        if (!$barang->delete()) {
            return back()->withErrors(['Barang gagal dihapus.']);
        }

        return back()->with(['success' => 'Barang berhasil dihapus']);
    }
}
