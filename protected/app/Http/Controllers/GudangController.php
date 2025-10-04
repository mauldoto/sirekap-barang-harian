<?php

namespace App\Http\Controllers;

// use App\Imports\GudangImport;
use App\Models\Gudang;
use App\Models\LogStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GudangController extends Controller
{
    public function index()
    {
        $gudang = Gudang::orderBy('nama')->get();
        return view('contents.gudang.index', compact('gudang'));
    }

    public function detail(Request $request, $id)
    {
        $type = $request->type;
        $gudang = Gudang::where('id', $id)->first();
        // if ($type != 'json') {
        //     if (!$gudang) {
        //         return back()->withErrors(['Gudang tidak ditemukan']);
        //     }

        //     return; //maybe view
        // }

        if (!$gudang) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $gudang
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'nullable|string',
            'nama' => 'required|string',
            'deskripsi' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $newGudang = new Gudang();
        $newGudang->kode = $request->kode ? $request->kode : generateReference('G');
        $newGudang->nama = $request->nama;
        $newGudang->deskripsi = $request->deskripsi;
        $newGudang->input_by = $request->user()->id;

        if (!$newGudang->save()) {
            return back()->withErrors(['Gudang gagal tersimpan.'])->withInput();
        }

        return back()->with(['success' => 'Gudang berhasil tersimpan.']);
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

        $gudang = Gudang::where('id', $id)->first();
        if (!$gudang) {
            return back()->withErrors(['Gudang tidak ditemukan.']);
        }

        $gudang->nama = $request->nama;
        $gudang->deskripsi = $request->deskripsi;

        if (!$gudang->save()) {
            return back()->withErrors(['Gudang gagal terupdate.'])->withInput();
        }

        return back()->with(['success' => 'Gudang berhasil terupdate.']);
    }

    public function delete(Request $request, $id)
    {
        $gudang = Gudang::where('id', $id)->first();
        if (!$gudang) {
            return back()->withErrors(['Gudang tidak ditemukan.']);
        }

        $checkOnStock = LogStok::where('id_gudang', $gudang->id)->first();
        if ($checkOnStock) return back()->withErrors(['Gudang tidak bisa dihapus karena sudah ada dalam stok, silakan kontak Administrator!']);

        if (!$gudang->delete()) {
            return back()->withErrors(['Gudang gagal dihapus.']);
        }

        return back()->with(['success' => 'Gudang berhasil dihapus']);
    }

    // public function import(Request $request)
    // {
    //     try {
    //         Excel::import(new GudangImport, $request->file('import_gudang'));
    //     } catch (\Throwable $th) {
    //         return back()->withErrors(['Import data gudang gagal.']);
    //     }

    //     return back()->with(['success' => 'Import data gudang berhasil.']);
    // }

    // public function downloadFormat(Request $request)
    // {
    //     return response()->download(asset('assets/files/import_gudang_format.xlsx'));
    // }
}
