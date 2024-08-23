<?php

namespace App\Http\Controllers;

use App\Exports\AktivitasExport;
use App\Exports\StokExport;
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
use Maatwebsite\Excel\Facades\Excel;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;


class ReportController extends Controller
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

    public function report(Request $request)
    {
        $startDate = $request->dari ? Carbon::createFromFormat('Y-m-d', $request->dari)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $report = $request->report;

        switch ($report) {
            case 'stok':
                $barang = Barang::get();

                return view('contents.report.report', compact('startDate', 'endDate', 'barang', 'report'));
                break;
            case 'penggunaan-stok':
                $barang = Barang::orderBy('nama')->get();
                $lokasi = Lokasi::orderBy('nama')->get();

                return view('contents.report.report', compact('startDate', 'endDate', 'barang', 'report', 'lokasi'));
                break;

            default:
                # code...
                break;
        }


        return view('contents.report.report', compact('startDate', 'endDate', 'report'));
    }

    public function processReport(Request $request)
    {
        // $stok = LogStok::select('id_barang', 'is_new', DB::raw('SUM(qty) as sumqty'));
        // if ($request->barang) {
        //     $stok = $stok->whereIn('id_barang', $request->barang);
        // }
        // $stok = $stok->with('barang')
        //     ->groupBy('id_barang', 'is_new')->get();

        // return Excel::download(new StokExport($stok), 'stok.xlsx');

        $startDate = $request->dari ? Carbon::createFromFormat('Y-m-d', $request->dari)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $report = $request->report;
        return $this->reportAktivitas($request, $startDate, $endDate);
        switch ($report) {
            case 'stok':
                return $this->reportStok($request);
                break;
            case 'penggunaan-stok':
                return $this->reportAktivitas($request, $startDate, $endDate);
                break;

            default:
                # code...
                break;
        }
    }

    protected function reportStok($request)
    {
        $stok = LogStok::select('id_barang', 'is_new', DB::raw('SUM(qty) as sumqty'));
        if ($request->barang) {
            $stok = $stok->whereIn('id_barang', $request->barang);
        }
        $stok = $stok->with('barang')
            ->groupBy('id_barang', 'is_new')->get();

        return Excel::download(new StokExport($stok), 'stok.xlsx');

        try {

            return Excel::download(new StokExport($stok), 'stok.xlsx');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function reportAktivitas($request, $startDate, $endDate)
    {
        $aktivitas = Aktivitas::with(['lokasi', 'sublokasi', 'teknisi' => ['karyawan']])
            ->where('tanggal_berangkat', '>=', $startDate)
            ->where('tanggal_berangkat', '<=', $endDate)
            ->get();

        try {
            return Excel::download(new AktivitasExport($aktivitas), 'aktivitas.xlsx');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
