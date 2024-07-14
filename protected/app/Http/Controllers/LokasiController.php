<?php

namespace App\Http\Controllers;

use App\Imports\LokasiImport;
use App\Imports\SubLokasiImport;
use App\Models\Lokasi;
use App\Models\SubLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();
        $sublokasi = SubLokasi::with('lokasi')->get();
        return view('contents.lokasi.index', compact('lokasi', 'sublokasi'));
    }

    public function detail(request $request, $id)
    {
        $type = $request->type;
        $lokasi = Lokasi::where('id', $id)->first();
        // if ($type != 'json') {
        //     if (!$lokasi) {
        //         return back()->withErrors(['lokasi tidak ditemukan']);
        //     }

        //     return; //maybe view
        // }

        if (!$lokasi) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $lokasi
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required|string',
            'kode' => 'nullable|string',
            'deskripsi_lokasi' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $newLokasi = new Lokasi();
        $newLokasi->nama = $request->nama_lokasi;
        $newLokasi->deskripsi = $request->deskripsi_lokasi;
        $newLokasi->kode = $request->kode ? $request->kode : generateReference('L');
        $newLokasi->input_by = $request->user()->id;


        if (!$newLokasi->save()) {
            return back()->withErrors(['Lokasi gagal tersimpan.']);
        }

        return back()->with(['success' => 'Lokasi berhasil tersimpan.']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lokasi' => 'required|string',
            'deskripsi_lokasi' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $lokasi = Lokasi::where('id', $id)->first();
        if (!$lokasi) {
            return back()->withErrors(['Lokasi tidak ditemukan.']);
        }

        $lokasi->nama = $request->nama_lokasi;
        $lokasi->deskripsi = $request->deskripsi_lokasi;
        $lokasi->input_by = $request->user()->id;


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

    public function subdetail(request $request, $id)
    {
        $type = $request->type;
        $sublokasi = SubLokasi::where('id', $id)->first();
        // if ($type != 'json') {
        //     if (!$sublokasi) {
        //         return back()->withErrors(['sublokasi tidak ditemukan']);
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

    public function substore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sublokasi' => 'required|string',
            'kode' => 'nullable|string',
            'deskripsi_sublokasi' => 'nullable',
            'lokasi' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $newSubLokasi = new SubLokasi();
        $newSubLokasi->nama = $request->nama_sublokasi;
        $newSubLokasi->id_lokasi = $request->lokasi;
        $newSubLokasi->deskripsi = $request->deskripsi_sublokasi;
        $newSubLokasi->kode = $request->kode ? $request->kode : generateReference('SL');
        $newSubLokasi->input_by = $request->user()->id;


        if (!$newSubLokasi->save()) {
            return back()->withErrors(['Sublokasi gagal tersimpan.']);
        }

        return back()->with(['success' => 'Sublokasi berhasil tersimpan.']);
    }

    public function subupdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_sublokasi' => 'required|string',
            'deskripsi_sublokasi' => 'nullable',
            'lokasi' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $sublokasi = SubLokasi::where('id', $id)->first();
        if (!$sublokasi) {
            return back()->withErrors(['Sublokasi tidak ditemukan.']);
        }

        $sublokasi->nama = $request->nama_sublokasi;
        $sublokasi->deskripsi = $request->deskripsi_sublokasi;
        $sublokasi->input_by = $request->user()->id;

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

    public function importLokasi(Request $request)
    {
        try {
            Excel::import(new LokasiImport, $request->file('import_lokasi'));
        } catch (\Throwable $th) {
            return back()->withErrors(['Import data lokasi gagal.', $th->getMessage()]);
        }

        return back()->with(['success' => 'Import data lokasi berhasil.']);
    }

    public function importSubLokasi(Request $request)
    {
        try {
            Excel::import(new SubLokasiImport, $request->file('import_sublokasi'));
        } catch (\Throwable $th) {
            return back()->withErrors(['Import data sublokasi gagal.', $th->getMessage()]);
        }

        return back()->with(['success' => 'Import data sublokasi berhasil.']);
    }
}
