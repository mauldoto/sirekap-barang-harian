<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\AktivitasKaryawan;
use App\Models\Karyawan;
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
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $aktivitas = Aktivitas::with(['lokasi', 'sublokasi', 'user'])
            ->where('tanggal_berangkat', '>=', $startDate)
            ->where('tanggal_berangkat', '<=', $endDate)
            ->get();

        $reportAktivitas = Aktivitas::orderBy('tanggal_berangkat', 'DESC')->get();

        return view('contents.aktivitas.index', compact('aktivitas', 'startDate', 'endDate', 'reportAktivitas'));
    }

    public function getDetail(request $request, $id)
    {
        $aktivitas = Aktivitas::where('id', $id)->with('teknisi.karyawan')->first();
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
        $karyawan = Karyawan::all();
        $lokasi = Lokasi::all();
        return view('contents.aktivitas.input', compact('karyawan', 'lokasi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noref' => 'required|string',
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

        $newActivity = new Aktivitas();
        $newActivity->no_referensi = $request->noref;
        $newActivity->id_lokasi = $request->lokasi;
        $newActivity->id_sub_lokasi = $request->sublokasi;
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

        DB::commit();
        return back()->with(['success' => 'Input aktivitas berhasil.']);
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
        return $pdf->stream($aktivitas->no_referensi.'.pdf');
    }
}
