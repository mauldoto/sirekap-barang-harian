<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogStok;
use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index()
    {
        $stok = LogStok::select('id_barang', DB::raw('SUM(qty) as sumqty'))
            ->with('barang')
            ->groupBy('id_barang')->get();
        return view('contents.stok.index', compact('stok'));
    }

    public function viewStokMasuk()
    {
        $barang = Barang::all();
        return view('contents.stok.stokin', compact('barang'));
    }

    public function storeStokMasuk(Request $request)
    {
        DB::beginTransaction();

        $newStokIn = new Stok();
        $newStokIn->no_referensi = $request->noref;
        $newStokIn->tanggal = $request->tanggal;
        $newStokIn->type = 'masuk';

        if (!$newStokIn->save()) {
            DB::rollBack();
            return back()->withErrors(['Input stok masuk gagal.'])->withInput();
        }

        foreach ($request->barang as $key => $barang) {
            $newLogStok = new LogStok();
            $newLogStok->id_stok = $newStokIn->id;
            $newLogStok->id_barang = $barang['item'];
            $newLogStok->qty = $barang['qty'];

            if (!$newLogStok->save()) {
                DB::rollBack();
                return back()->withErrors(['Error input log stok.'])->withInput();
            }
        }

        DB::commit();
        return back()->with(['success' => 'Input stok masuk berhasil.']);        
    }

    // this section is for out stock
    public function viewStokKeluar()
    {
        $barang = Barang::all();
        return view('contents.stok.stokout', compact('barang'));
    }

    public function storeStokKeluar(Request $request)
    {
        DB::beginTransaction();

        $newStokOut = new Stok();
        $newStokOut->no_referensi = $request->noref;
        $newStokOut->id_aktivitas = $request->aktivitas ?? null ;
        $newStokOut->tanggal = $request->tanggal;
        $newStokOut->type = 'keluar';

        if (!$newStokOut->save()) {
            DB::rollBack();
            return back()->withErrors(['Input stok keluar gagal.'])->withInput();
        }

        foreach ($request->barang as $key => $barang) {
            $newLogStok = new LogStok();
            $newLogStok->id_stok = $newStokOut->id;
            $newLogStok->id_barang = $barang['item'];
            $newLogStok->qty = -$barang['qty'];

            if (!$newLogStok->save()) {
                DB::rollBack();
                return back()->withErrors(['Error input log stok.'])->withInput();
            }
        }

        DB::commit();
        return back()->with(['success' => 'Input stok keluar berhasil.']);        
    }
}
