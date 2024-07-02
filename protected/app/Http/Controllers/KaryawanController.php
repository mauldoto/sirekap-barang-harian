<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::all();
        return view('contents.karyawan.index', compact('karyawan'));
    }

    public function detail(request $request, $id)
    {
        $type = $request->type;
        $karyawan = Karyawan::where('id', $id)->first();
        // if ($type != 'json') {
        //     if (!$karyawan) {
        //         return back()->withErrors(['karyawan tidak ditemukan']);
        //     }

        //     return; //maybe view
        // }

        if (!$karyawan) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $karyawan
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'kode' => 'nullable|string',
            'deskripsi' => 'nullable',
        ]);
 
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $newKaryawan = new Karyawan();
        $newKaryawan->kode = $request->kode ? $request->kode : generateReference('K');
        $newKaryawan->nama = $request->nama;
        $newKaryawan->deskripsi = $request->deskripsi;

        if (!$newKaryawan->save()) {
            return back()->withErrors(['Karyawan gagal tersimpan.'])->withInput();
        }

        return back()->with(['success' => 'Karyawan berhasil tersimpan.']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'deskripsi' => 'nullable',
        ]);
 
        if ($validator->fails()) {
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $karyawan = Karyawan::where('id', $id)->first();
        if (!$karyawan) {
            return back()->withErrors(['karyawan tidak ditemukan.']);
        }

        $karyawan = new Karyawan();
        $karyawan->nama = $request->nama;
        $karyawan->deskripsi = $request->deskripsi;

        if (!$karyawan->save()) {
            return back()->withErrors(['Karyawan gagal terupdate.'])->withInput();
        }

        return back()->with(['success' => 'Karyawan berhasil terupdate.']);
    }

    public function delete(Request $request, $id)
    {
        $karyawan = Karyawan::where('id', $id)->first();
        if (!$karyawan) {
            return back()->withErrors(['karyawan tidak ditemukan.']);
        }

        if (!$karyawan->delete()) {
            return back()->withErrors(['data karyawan gagal dihapus.']);
        }

        return back()->with(['success' => 'data karyawan berhasil dihapus.']);
    }
}
