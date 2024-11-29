<?php

namespace App\Http\Controllers;

use App\Imports\KaryawanImport;
use App\Models\Aktivitas;
use App\Models\AktivitasKaryawan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
        $newKaryawan->input_by = $request->user()->id;


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

        $checkKaryawan = AktivitasKaryawan::where('id_karyawan', $karyawan->id)->first();
        if ($checkKaryawan) return back()->withErrors(['Data karyawan tidak bisa dihapus karena sudah ada dalam histori aktivitas, silakan kontak Administrator!']);

        if (!$karyawan->delete()) {
            return back()->withErrors(['data karyawan gagal dihapus.']);
        }

        return back()->with(['success' => 'data karyawan berhasil dihapus.']);
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new KaryawanImport, $request->file('import_karyawan'));
        } catch (\Throwable $th) {
            return back()->withErrors(['Import data karyawan gagal.', $th->getMessage()]);
        }

        return back()->with(['success' => 'Import data karaywan berhasil.']);
    }
}
