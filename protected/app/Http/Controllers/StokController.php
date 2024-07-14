<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Barang;
use App\Models\LogStok;
use App\Models\Stok;
use Carbon\Carbon;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index()
    {
        $stok = LogStok::select('id_barang', DB::raw('SUM(qty) as sumqty'), 'is_new')
            ->with('barang')
            ->groupBy('id_barang', 'is_new')->get();

        $barang = Barang::get();
        return view('contents.stok.index', compact('stok', 'barang'));
    }

    public function log(Request $request)
    {
        $startDate = $request->dari ? Carbon::createFromFormat('Y-m-d', $request->dari)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $type = $request->filter_type;

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $stokM = Stok::where('tanggal', '>=', $startDate)
            ->where('tanggal', '<=', $endDate);

        if ($type) {
            $stokM = $stokM->where('type', $type);
        }

        $stokM = $stokM->pluck('id');

        $stok = LogStok::whereIn('id_stok', $stokM)->with(['barang', 'stok' => ['user', 'aktivitas']])->get();

        return view('contents.stok.log', compact('stok', 'startDate', 'endDate', 'type'));
    }

    public function viewStokMasuk()
    {
        $barang = Barang::all();
        return view('contents.stok.stokin', compact('barang'));
    }

    public function storeStokMasuk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noref'         => 'required|string',
            'tanggal'       => 'required|date',
            'barang'        => 'required|array',
            'barang.*.item' => 'required',
            'barang.*.qty'  => 'required',
            'barang.*.kondisi'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        $newStokIn = new Stok();
        $newStokIn->no_referensi = $request->noref;
        $newStokIn->tanggal = $request->tanggal;
        $newStokIn->type = 'masuk';
        $newStokIn->input_by = $request->user()->id;


        if (!$newStokIn->save()) {
            DB::rollBack();
            return back()->withErrors(['Input stok masuk gagal.'])->withInput();
        }

        foreach ($request->barang as $key => $barang) {
            $newLogStok = new LogStok();
            $newLogStok->id_stok = $newStokIn->id;
            $newLogStok->id_barang = $barang['item'];
            $newLogStok->qty = $barang['qty'];
            $newLogStok->is_new = isset($barang['kondisi']) ? true : false;

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
        $aktivitas = Aktivitas::all();
        return view('contents.stok.stokout', compact('barang', 'aktivitas'));
    }

    public function storeStokKeluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noref'         => 'required|string',
            'tanggal'       => 'required|date',
            'barang'        => 'required|array',
            'barang.*.item' => 'required',
            'barang.*.qty'  => 'required',
            'barang.*.kondisi'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        $newStokOut = new Stok();
        $newStokOut->no_referensi = $request->noref;
        $newStokOut->id_aktivitas = $request->aktivitas ?? null;
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
            $newLogStok->is_new = isset($barang['kondisi']) ? true : false;

            if (!$newLogStok->save()) {
                DB::rollBack();
                return back()->withErrors(['Error input log stok.'])->withInput();
            }
        }

        DB::commit();
        return back()->with(['success' => 'Input stok keluar berhasil.']);
    }

    public function exportPdf(Request $request)
    {

        $stok = LogStok::select('id_barang', DB::raw('SUM(qty) as sumqty'));
        if ($request->barang) {
            $stok = $stok->whereIn('id_barang', $request->barang);
        }
        $stok = $stok->with('barang')
            ->groupBy('id_barang')->get();

        $pdf = LaravelMpdf::loadview('exports.pdf.stok', ['stok' => $stok, 'tanggal' => Carbon::now()->format('d-m-Y')]);
        return $pdf->stream('report-stok.pdf');
    }
}
