<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\LogStok;
use App\Models\Lokasi;
use App\Models\Stok;
use App\Models\SubLokasi;
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

        return view('contents.stok.index', compact('stok'));
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
            'barang.*.bekas'  => 'nullable',
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
        $newStokIn->deskripsi = $request->keterangan;


        if (!$newStokIn->save()) {
            DB::rollBack();
            return back()->withErrors(['Input stok masuk gagal.'])->withInput();
        }

        foreach ($request->barang as $key => $barang) {
            $newLogStok = new LogStok();
            $newLogStok->id_stok = $newStokIn->id;
            $newLogStok->id_barang = $barang['item'];
            $newLogStok->qty = $barang['qty'];
            $newLogStok->is_new = array_key_exists('bekas', $barang) ? false : true;

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
        $stok = LogStok::select('id_barang', DB::raw('SUM(qty) as sumqty'), 'is_new')
            ->with('barang')
            ->having('sumqty', '>', 0)
            ->groupBy('id_barang', 'is_new')->get();

        $ids = array_map(function ($item) {
            return $item['id_barang'];
        }, $stok->toArray());

        $barang = Barang::whereIn('id', $ids)->get();

        foreach ($barang as $key => $item) {
            foreach ($stok as $key => $stokValue) {
                if ($stokValue->id_barang == $item->id) {
                    if ($stokValue->is_new) {
                        $item->new = $stokValue->sumqty;
                    } else {
                        $item->second = $stokValue->sumqty;
                    }
                }
            }
        }
        $aktivitas = Aktivitas::whereIn('status', ['waiting', 'progress'])->orderBy('tanggal_berangkat', 'DESC')->get();
        return view('contents.stok.stokout', compact('barang', 'aktivitas'));
    }

    public function storeStokKeluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noref'         => 'required|string',
            'tanggal'       => 'required|date',
            'aktivitas'     => 'required',
            'barang'        => 'required|array',
            'barang.*.item' => 'required',
            'barang.*.qty'  => 'required',
            'barang.*.bekas'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        $idsBarang = array_map(function($value){
            return $value['item'];
        }, $request->barang);

        // dd($idsBarang);
        $stokLogs = LogStok::whereIn('id_barang', $idsBarang)->select('id_barang', 'is_new', DB::raw('SUM(qty) as sumqty'))->groupBy('id_barang', 'is_new')->get()->toArray();

        $newStokOut = Stok::where('id_aktivitas', $request->aktivitas)->first();
        
        if (!$newStokOut) {
            $newStokOut = new Stok();
            $newStokOut->no_referensi = $request->noref;
            $newStokOut->id_aktivitas = $request->aktivitas ?? null;
            $newStokOut->tanggal = $request->tanggal;
            $newStokOut->type = 'keluar';
            $newStokOut->deskripsi = $request->keterangan;
            $newStokOut->input_by = $request->user()->id;
        }

        if (!$newStokOut->save()) {
            DB::rollBack();
            return back()->withErrors(['Input stok keluar gagal.'])->withInput();
        }

        foreach ($request->barang as $key => $barang) {

            $checkStok = array_filter($stokLogs, function($value) use ($barang){
                if ($value['id_barang'] == $barang['item'] && $value['is_new'] == !array_key_exists('bekas', $barang) && $value['sumqty'] >= $barang['qty']) {
                    return true;
                }
            });

            if (!$checkStok) {
                DB::rollBack();
                return back()->withErrors(['Error input log stok, ada barang dengan stok minus.'])->withInput();
            }

            $newLogStok = new LogStok();
            $newLogStok->id_stok = $newStokOut->id;
            $newLogStok->id_barang = $barang['item'];
            $newLogStok->qty = -$barang['qty'];
            $newLogStok->is_new = array_key_exists('bekas', $barang) ? false : true;

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

        $stok = LogStok::select('id_barang', 'is_new', DB::raw('SUM(qty) as sumqty'));
        if ($request->barang) {
            $stok = $stok->whereIn('id_barang', $request->barang);
        }
        $stok = $stok->with('barang')
            ->groupBy('id_barang', 'is_new')->get();

        $pdf = LaravelMpdf::loadview('exports.pdf.stok', ['stok' => $stok, 'tanggal' => Carbon::now()->format('d-m-Y')]);
        return $pdf->stream('report-stok.pdf');
    }

    public function rencanaSK()
    {


        $stok = LogStok::select('id_barang', DB::raw('SUM(qty) as sumqty'), 'is_new')
            ->with('barang')
            ->having('sumqty', '>', 0)
            ->groupBy('id_barang', 'is_new')->get();

        $ids = array_map(function ($item) {
            return $item['id_barang'];
        }, $stok->toArray());

        $barang = Barang::whereIn('id', $ids)->get();

        foreach ($barang as $key => $item) {
            foreach ($stok as $key => $stokValue) {
                if ($stokValue->id_barang == $item->id) {
                    if ($stokValue->is_new) {
                        $item->new = $stokValue->sumqty;
                    } else {
                        $item->second = $stokValue->sumqty;
                    }
                }
            }
        }

        $lokasi = Lokasi::all();
        $karyawan = Karyawan::all();

        return view('contents.stok.rencana', compact('barang', 'lokasi', 'karyawan'));
    }

    public function cetakRencanaSK(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal'       => 'required|date',
            'lokasi'        => 'required',
            'sublokasi'     => 'required|array',
            'teknisi'       => 'required',
            // 'barang'        => 'required|array',
            // 'barang.*.item' => 'required',
            // 'barang.*.qty'  => 'required',
            // 'barang.*.bekas'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $lokasi = Lokasi::where('id', $request->lokasi)->first();

        $sublokasi = SubLokasi::where('id_lokasi', $lokasi->id)->get()->toArray();

        $karyawan = Karyawan::whereIn('id', $request->teknisi)->get();

        $dbBarang = Barang::get()->toArray();

        $data = [];
        foreach ($request->selected_sublokasi as $key => $subSelected) {
            $dataSub = array_values(array_filter($sublokasi, function ($item) use ($subSelected) {
                if ($item['id'] == $subSelected) {
                    return $item;
                }
            }))[0];

            $data[$subSelected] = $dataSub;

            $barangIds = array_unique(array_column($request['lokasi_' . $subSelected], 'item'));
            $barangSelected = array_filter($dbBarang, function ($item) use ($barangIds) {
                if (in_array($item['id'], $barangIds)) {
                    return $item;
                }
            });
    
            $barangFinal = [];
            foreach ($request['lokasi_' . $subSelected] as $key => $barang) {
                foreach ($barangSelected as $key => $dbb) {
                    if ($barang['item'] == $dbb['id']) {
                        $dbb['kondisi'] = array_key_exists('bekas', $barang) ? 'Bekas' : 'Baru';
                        $dbb['qty'] = $barang['qty'];
    
                        array_push($barangFinal, $dbb);
                    }
                }
            }

            $data[$subSelected]['barang'] = $barangFinal;
        }

        $pdf = LaravelMpdf::loadview('exports.pdf.cetak-perencanaan', [
            'tanggal'   => Carbon::createFromFormat('Y-m-d', $request->tanggal)->format('d-m-Y'),
            'lokasi'    => $lokasi,
            'karyawan'  => $karyawan,
            'barang'    => $data
        ]);

        return $pdf->stream('pengeluaran-stok-'. $lokasi->nama . '-' . date('dmY') .'.pdf');
    }
}
