<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\AktivitasKaryawan;
use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\LogStok;
use App\Models\Lokasi;
use App\Models\SubLokasi;
use App\Models\Stok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;


class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->dari ? Carbon::createFromFormat('Y-m-d', $request->dari)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->addMonths(2)->format('Y-m-d');

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $aktivitas = Aktivitas::with(['lokasi', 'sublokasi', 'user', 'stok'])
            ->where('tanggal_berangkat', '>=', $startDate)
            ->where('tanggal_berangkat', '<=', $endDate)
            ->get();

        $reportAktivitas = Aktivitas::orderBy('tanggal_berangkat', 'DESC')->get();

        return view('contents.aktivitas.index', compact('aktivitas', 'startDate', 'endDate', 'reportAktivitas'));
    }

    public function getDetail(request $request, $id)
    {
        $aktivitas = Aktivitas::where('id', $id)->with(['teknisi.karyawan', 'lokasi', 'sublokasi'])->first();
        $barang = Stok::where('type', 'keluar')->where('id_aktivitas', $aktivitas->id)->with('stok.barang')->first();

        $aktivitas->barang = $barang ? $barang->stok : [];
        // if ($type != 'json') {
        //     if (!$karyawan) {
        //         return back()->withErrors(['karyawan tidak ditemukan']);
        //     }

        //     return; //maybe view
        // }

        if (!$aktivitas) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $aktivitas
        ]);
    }


    public function getSubLokasi(Request $request, $ids)
    {
        $sublokasi = SubLokasi::where('id_lokasi', $ids)->get();
        // if ($type != 'json') {
        //     if (!$sublokasi) {
        //         return back()->withErrors(['Barang tidak ditemukan']);
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

    public function input()
    {
        $karyawan = Karyawan::orderBy('nama')->get();
        $lokasi = Lokasi::orderBy('nama')->get();
        return view('contents.aktivitas.input', compact('karyawan', 'lokasi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi' => 'required',
            'sublokasi' => 'required|array',
            'sublokasi.*' => 'required',
            'teknisi' => 'required|array',
            'teknisi.*' => 'required',
            'deskripsi' => 'nullable',
            'tanggal_berangkat' => 'required|date',
            'tanggal_pulang' => 'required|date',
            'status' => 'nullable|in:waiting,progress,done,cancel',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->tanggal_berangkat);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->tanggal_pulang);

        if ($startDate > $endDate) {
            return back()->withErrors(['Tanggal berangkat tidak boleh lebih dari tanggal pulang.'])->withInput();
        }

        DB::beginTransaction();

        foreach ($request->sublokasi as $key => $sublokasi) {
            $newActivity = new Aktivitas();
            $newActivity->no_referensi = generateReference('JOB');
            $newActivity->id_lokasi = $request->lokasi;
            $newActivity->id_sub_lokasi = $sublokasi;
            $newActivity->tanggal_berangkat = $request->tanggal_berangkat;
            $newActivity->tanggal_pulang = $request->tanggal_pulang;
            $newActivity->deskripsi = $request->deskripsi;
            $newActivity->input_by = $request->user()->id;
            $newActivity->status = 'waiting';
    
            if (!$newActivity->save()) {
                DB::rollBack();
                return back()->withErrors(['Input data aktivitas gagal.'])->withInput();
            }
    
            foreach ($request->teknisi as $key => $teknisi) {
                $checkTeknisi = Karyawan::where('id', $teknisi)->first();
                if (!$checkTeknisi) {
                    DB::rollBack();
                    return back()->withErrors(['Input data teknisi ke aktivitas gagal, data teknisi tidak ditemukan.'])->withInput();
                }
    
                $inpTeknisi = new AktivitasKaryawan();
                $inpTeknisi->id_aktivitas = $newActivity->id;
                $inpTeknisi->id_karyawan = $checkTeknisi->id;
    
                if (!$inpTeknisi->save()) {
                    DB::rollBack();
                    return back()->withErrors(['Input data teknisi ke aktivitas gagal.'])->withInput();
                }
            }
        }

        DB::commit();
        return back()->with(['success' => 'Input aktivitas berhasil.']);
    }

    public function edit($tiket)
    {
        $karyawan = Karyawan::all();
        $lokasi = Lokasi::all();
        $aktivitas = Aktivitas::where('no_referensi', $tiket)->with(['teknisi.karyawan', 'lokasi', 'sublokasi'])->first();
        if (!$aktivitas) {
            return back()->withErrors(['Aktivitas dengan no tiket ' . $tiket . ' tidak ditemukan.']);
        }

        if (in_array($aktivitas->status, ['done', 'cancel'])) {
            return back()->withErrors(['Aktivitas sudah berstatus DONE dan CANCEL tidak dapat diupdate.']);
        }

        $sublokasi = SubLokasi::where('id_lokasi', $aktivitas->id_lokasi)->get();

        $teknisiArr = [];
        foreach ($aktivitas->teknisi as $key => $value) {
            array_push($teknisiArr, $value->id_karyawan);
        }

        return view('contents.aktivitas.edit', compact('karyawan', 'lokasi', 'aktivitas', 'teknisiArr', 'sublokasi'));
    }

    public function update(Request $request, $tiket)
    {
        $validator = Validator::make($request->all(), [
            'lokasi' => 'required',
            'sublokasi' => 'required',
            'teknisi' => 'required|array',
            'teknisi.*' => 'required',
            'deskripsi' => 'nullable',
            'tanggal_berangkat' => 'required|date',
            'tanggal_pulang' => 'required|date',
            'status' => 'nullable|in:waiting,progress,done,cancel',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $request->tanggal_berangkat);
        $endDate = Carbon::createFromFormat('Y-m-d', $request->tanggal_pulang);

        if ($startDate > $endDate) {
            return back()->withErrors(['Tanggal berangkat tidak boleh lebih dari tanggal pulang.'])->withInput();
        }

        DB::beginTransaction();

        $activity = Aktivitas::where('no_referensi', $tiket)->first();
        if (!$activity) {
            return redirect(route('aktivitas.index'))->withErrors(['Aktivitas dengan no tiket ' . $tiket . ' tidak ditemukan.']);
        }

        $activity->id_lokasi = $request->lokasi;
        $activity->id_sub_lokasi = $request->sublokasi;
        $activity->tanggal_berangkat = $request->tanggal_berangkat;
        $activity->tanggal_pulang = $request->tanggal_pulang;
        $activity->deskripsi = $request->deskripsi;
        $activity->input_by = $request->user()->id;

        if (!$activity->save()) {
            DB::rollBack();
            return back()->withErrors(['Update data aktivitas gagal.'])->withInput();
        }

        AktivitasKaryawan::where('id_aktivitas', $activity->id)->delete();

        foreach ($request->teknisi as $key => $teknisi) {
            $checkTeknisi = Karyawan::where('id', $teknisi)->first();
            if (!$checkTeknisi) {
                DB::rollBack();
                return back()->withErrors(['Update data teknisi ke aktivitas gagal, data teknisi tidak ditemukan.'])->withInput();
            }

            $inpTeknisi = new AktivitasKaryawan();
            $inpTeknisi->id_aktivitas = $activity->id;
            $inpTeknisi->id_karyawan = $checkTeknisi->id;

            if (!$inpTeknisi->save()) {
                DB::rollBack();
                return back()->withErrors(['Update data teknisi ke aktivitas gagal.'])->withInput();
            }
        }

        DB::commit();
        return back()->with(['success' => 'Update aktivitas berhasil.']);
    }

    public function updateStatus(Request $request, $tiket)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:waiting,progress,done,cancel',
            'deskripsi' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $activity = Aktivitas::where('no_referensi', $tiket)->first();
        if (!$activity) {
            return redirect(route('aktivitas.index'))->withErrors(['Aktivitas dengan no tiket ' . $tiket . ' tidak ditemukan.']);
        }

        if (in_array($activity->status, ['done', 'cancel'])) {
            return back()->withErrors(['Aktivitas sudah berstatus DONE dan CANCEL tidak dapat diupdate.']);
        }

        $activity->status = $request->status;
        if ($request->status == 'cancel') {
            $activity->deskripsi = $activity->deskripsi . ' #[CANCEL]: ' . $request->deskripsi;

            $stok = Stok::where('id_aktivitas', $activity->id)->first();

            if ($stok) {
                $logs = LogStok::where('id_stok', $stok->id)->delete();
                if (!$logs) {
                    return back()->withErrors(['Gagal hapus data tiket, on delete stock out']);
                }

                if(!$stok->delete()){
                    return back()->withErrors(['Gagal hapus data tiket, on delete master stock out']);
                }
            }
        }

        if ($request->status == 'done') {
            $activity->deskripsi = $activity->deskripsi . ' #[DONE]: ' . $request->deskripsi;
        }

        if (!$activity->save()) {
            return back()->withErrors(['Update status aktivitas gagal.'])->withInput();
        }

        return back()->with(['success' => 'Update Status aktivitas ' . $tiket . ' menjadi ' . strtoupper($request->status) . ' berhasil']);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->dari ? Carbon::createFromFormat('Y-m-d', $request->dari)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $aktivitas = Aktivitas::with(['lokasi', 'sublokasi', 'teknisi' => ['karyawan']])
            ->where('tanggal_berangkat', '>=', $startDate)
            ->where('tanggal_berangkat', '<=', $endDate)
            ->get();

        // return view('exports.pdf.tiket-aktivitas', compact('aktivitas'));

        $pdf = LaravelMpdf::loadview('exports.pdf.tiket-aktivitas', ['aktivitas' => $aktivitas]);
        return $pdf->stream('report-aktivitas.pdf');
    }

    public function printTiket(Request $request, $tiket)
    {

        $aktivitas = Aktivitas::where('no_referensi', $tiket)->with(['teknisi.karyawan', 'lokasi', 'sublokasi'])->first();
        $barang = Stok::where('type', 'keluar')->where('id_aktivitas', $aktivitas->id)->with('stok.barang')->first();

        $aktivitas->barang = $barang ? $barang->stok : [];
        // return view('exports.pdf.tiket-aktivitas', compact('aktivitas'));

        $pdf = LaravelMpdf::loadview('exports.pdf.tiket-aktivitas', ['aktivitas' => $aktivitas]);
        return $pdf->stream($aktivitas->no_referensi . '.pdf');
    }

    public function editStockOut(Request $request, $tiket)
    {
        $aktivitas = Aktivitas::where('no_referensi', $tiket)->with('lokasi', 'sublokasi')->first();
        $realStok = Stok::where('id_aktivitas', $aktivitas->id)->first();
        $realStokLog = LogStok::select('id_barang', DB::raw('SUM(qty) as sumqty'), 'is_new')
            ->with('barang')
            ->where('id_stok', $realStok->id)
            ->groupBy('id_barang', 'is_new')
            ->get();

        // untuk new stok out
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

        return view('contents.aktivitas.edit-stok', compact('barang', 'aktivitas', 'realStokLog'));
    }

    public function postEditStockOut(request $request, $tiket)
    {
        // $validator = Validator::make($request->all(), [
        //     'barang'        => 'nullable|array',
        //     'barang.*.item' => 'required',
        //     'barang.*.qty'  => 'required',
        //     'barang.*.bekas'  => 'nullable',
        // ]);

        // if ($validator->fails()) {
        //     return back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $aktivitas = Aktivitas::where('no_referensi', $tiket)->with('lokasi', 'sublokasi')->first();
        $realStok = Stok::where('id_aktivitas', $aktivitas->id)->first();
        
        DB::beginTransaction();

        $idsOldBarang = array_map(function($value){
            return $value['barang'];
        }, ($request->input ?? []));

        LogStok::where('id_stok', $realStok->id)->whereNotIn('id_barang', $idsOldBarang)->delete();
        // if (!$deleteLogStok) {
        //     return back()->withErrors(['Error update stok, OICD'])->withInput();
        // }

        $idsBarang = array_map(function($value){
            return $value['item'];
        }, $request->barang);

        // dd($idsBarang);
        $stokLogs = LogStok::whereIn('id_barang', $idsBarang)->select('id_barang', 'is_new', DB::raw('SUM(qty) as sumqty'))->groupBy('id_barang', 'is_new')->get()->toArray();

        if ($request->barang[0]['item']) {
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
                $newLogStok->id_stok = $realStok->id;
                $newLogStok->id_barang = $barang['item'];
                $newLogStok->qty = -$barang['qty'];
                $newLogStok->is_new = array_key_exists('bekas', $barang) ? false : true;
    
                if (!$newLogStok->save()) {
                    DB::rollBack();
                    return back()->withErrors(['Error input log stok.'])->withInput();
                }
            }
        }

        DB::commit();
        return back()->with(['success' => 'Update stok keluar berhasil. ['.$tiket.']']);

    }

    public function inputStockOut(Request $request, $tiket)
    {
        $aktivitas = Aktivitas::where('no_referensi', $tiket)->with('lokasi', 'sublokasi')->first();
        $realStok = Stok::where('id_aktivitas', $aktivitas->id)->first();

        if ($realStok) {
            return back()->withErrors(['Stok untuk tiket '. $aktivitas->no_referensi .' sudah terinput, silakan menggunakan fitur edit.']);
        }

        // untuk new stok out
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

        return view('contents.aktivitas.input-stok', compact('barang', 'aktivitas'));
    }

    public function postInputStockOut(request $request, $tiket)
    {
        $validator = Validator::make($request->all(), [
            'noref'         => 'required|string',
            'barang'        => 'nullable|array',
            'barang.*.item' => 'required',
            'barang.*.qty'  => 'required',
            'barang.*.bekas'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $aktivitas = Aktivitas::where('no_referensi', $tiket)->with('lokasi', 'sublokasi')->first();
        
        DB::beginTransaction();

        $idsBarang = array_map(function ($value) {
            return $value['item'];
        }, $request->barang);

        $stokLogs = LogStok::whereIn('id_barang', $idsBarang)->select('id_barang', 'is_new', DB::raw('SUM(qty) as sumqty'))->groupBy('id_barang', 'is_new')->get()->toArray();

        $newStokOut = Stok::where('id_aktivitas', $request->aktivitas)->first();

        if (!$newStokOut) {
            $newStokOut = new Stok();
            $newStokOut->no_referensi = $request->noref;
            $newStokOut->id_aktivitas = $aktivitas;
            $newStokOut->tanggal = $aktivitas->tanggal_pulang;
            $newStokOut->type = 'keluar';
            $newStokOut->input_by = $request->user()->id;
        }

        if (!$newStokOut->save()) {
            DB::rollBack();
            return back()->withErrors(['Input stok keluar gagal.'])->withInput();
        }

        foreach ($request->barang as $key => $barang) {

            $checkStok = array_filter($stokLogs, function ($value) use ($barang) {
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
        return redirect(route('aktivitas.index'))->with(['success' => 'Input stok keluar berhasil.']);

    }

    public function hapusTiket(Request $request, $tiket)
    {
        
        $activity = Aktivitas::where('no_referensi', $tiket)->first();
        if (!$activity) {
            return redirect(route('aktivitas.index'))->withErrors(['Aktivitas dengan no tiket ' . $tiket . ' tidak ditemukan.']);
        }

        DB::beginTransaction();
        $stok = Stok::where('id_aktivitas', $activity->id)->first();

        if ($stok) {
            $logs = LogStok::where('id_stok', $stok->id)->delete();
            if (!$logs) {
                DB::rollBack();
                return back()->withErrors(['Gagal hapus data tiket, on delete stock out']);
            }

            if(!$stok->delete()){
                DB::rollBack();
                return back()->withErrors(['Gagal hapus data tiket, on delete master stock out']);
            }
        }

        if(!$activity->delete()){
            DB::rollBack();
            return back()->withErrors(['Gagal hapus data tiket']);
        }

        DB::commit();
        return back()->with(['success' => 'Tiket ' . $tiket . ' berhasil dihapus.']);
    }
}
