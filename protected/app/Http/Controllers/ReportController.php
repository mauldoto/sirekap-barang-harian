<?php

namespace App\Http\Controllers;

use App\Exports\AktivitasExport;
use App\Exports\AktivitasKaryawanExport;
use App\Exports\PenggunaanBarangExport;
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
            case 'stok-barang':
                $barang = Barang::get();

                return view('contents.report.report', compact('startDate', 'endDate', 'barang', 'report'));
                break;
            case 'penggunaan-barang':
                $barang = Barang::orderBy('nama')->get();
                $lokasi = Lokasi::orderBy('nama')->get();

                return view('contents.report.report', compact('startDate', 'endDate', 'barang', 'report', 'lokasi'));
                break;
            case 'aktivitas':
                $lokasi = Lokasi::orderBy('nama')->get();

                return view('contents.report.report', compact('startDate', 'endDate', 'report', 'lokasi'));
                break;
            case 'aktivitas-karyawan':
                $karyawan = Karyawan::orderBy('nama')->get();

                return view('contents.report.report', compact('startDate', 'endDate', 'karyawan', 'report'));
                break;

            default:
                # code...
                break;
        }


        return view('contents.report.report', compact('startDate', 'endDate', 'report'));
    }

    public function processReport(Request $request)
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
            case 'stok-barang':
                return $this->reportStok($request);
                break;
            case 'penggunaan-barang':
                return $this->reportPenggunanBarang($request, $startDate, $endDate);
                break;
            case 'aktivitas':
                return $this->reportAktivitas($request, $startDate, $endDate);
                break;
            case 'aktivitas-karyawan':
                return $this->reportAktivitasKaryawan($request, $startDate, $endDate);
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

        if ($request->export == 'excel') {
            try {
                return Excel::download(new StokExport($stok), 'report-stok-barang.xlsx');
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    protected function reportAktivitas($request, $startDate, $endDate)
    {
        $lokasi = $request->lokasi;
        $sublokasi = $request->sublokasi ? $request->sublokasi : [];

        $tsublokasi = implode(',', array_fill(0, count($sublokasi), '?'));

        $filter = [];

        $query = "SELECT 
                act.id,
                act.no_referensi,
                act.tanggal_berangkat,
                act.tanggal_pulang,
                act.deskripsi,
                stok.id as idstok,
                lokasi.nama as nama_lokasi,
                sub.nama as nama_sublokasi
            FROM aktivitas as act
            LEFT OUTER JOIN stok
            ON stok.id_aktivitas = act.id
            INNER JOIN lokasi
            ON act.id_lokasi = lokasi.id
            INNER JOIN sub_lokasi as sub
            ON act.id_sub_lokasi = sub.id
            WHERE act.status = 'done'";
        
        if ($lokasi) {
            $query .= " AND act.id_lokasi = ?";
            array_push($filter, $lokasi);
        }

        if (count($sublokasi) > 0) {
            $query .= " AND act.id_sub_lokasi IN ($tsublokasi)";
            array_push($filter, ...$sublokasi);
        }

        array_push($filter, ...[$startDate, $endDate]);

        $query .= " AND act.tanggal_berangkat >= ?
                AND act.tanggal_berangkat <= ?
                ORDER BY act.tanggal_berangkat";

        $dataMain = DB::select(
            $query,
            $filter
        );

        $idsStok = array_filter(array_map(function($item){
            return $item->idstok;
        }, $dataMain));

        $idsAktivitas = array_filter(array_map(function($item){
            return $item->id;
        }, $dataMain));

        $tIdStok = implode(',', array_fill(0, count($idsStok), '?'));
        if ($tIdStok == "") $tIdStok = "0";

        $tIdAktivitas = implode(',', array_fill(0, count($idsAktivitas), '?'));
        if ($tIdAktivitas == "") $tIdAktivitas = "0";


        $queryKaryawan = "SELECT
                        ak.id_aktivitas,
                        k.nama
                    FROM aktivitas_karyawan as ak
                    INNER JOIN karyawan as k
                    ON k.id = ak.id_karyawan
                    WHERE ak.id_aktivitas IN ($tIdAktivitas)";

        $dataKaryawan = DB::select(
            $queryKaryawan,
            [...$idsAktivitas]
        );

        $queryBarang = "SELECT
                        stok.id,
                        barang.nama,
                        barang.satuan,
                        ls.is_new,
                        ls.qty
                    FROM stok
                    INNER JOIN log_stok as ls
                    ON stok.id = ls.id_stok
                    INNER JOIN barang
                    ON barang.id = ls.id_barang
                    WHERE ls.id_stok IN ($tIdStok)";

        $dataBarang = DB::select(
            $queryBarang,
            [...$idsStok]
        );


        $dataFinal = array_map(function($aktivitas) use ($dataBarang, $dataKaryawan) {
            $datakar = [];
            foreach ($dataKaryawan as $key => $teknisi) {
                if ($teknisi->id_aktivitas == $aktivitas->id) {
                    array_push($datakar, $teknisi);
                }
            }

            $databar = [];
            foreach ($dataBarang as $key => $barang) {
                if ($barang->id == $aktivitas->idstok) {
                    array_push($databar, $barang);
                }
            }

            $aktivitas->karyawan = $datakar;
            $aktivitas->barang = $databar;

            return $aktivitas;
        }, $dataMain);

        if ($request->export == 'excel') {
            try {
                return Excel::download(new AktivitasExport($dataFinal, [$startDate, $endDate]), 'report-aktivitas.xlsx');
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        
    }

    protected function reportPenggunanBarang($request, $startDate, $endDate)
    {
        $lokasi = $request->lokasi;
        $sublokasi = $request->sublokasi ? $request->sublokasi : [];
        $barang = $request->barang ? $request->barang : [];

        $tsublokasi = implode(',', array_fill(0, count($sublokasi), '?'));
        $tbarang = implode(',', array_fill(0, count($barang), '?'));

        $filter = [];

        $query = "SELECT 
                act.id_lokasi,
                act.id_sub_lokasi,
                ls.id_barang as ls_idbarang,
                ls.is_new,
                lokasi.nama as nama_lokasi,
                sub.nama as nama_sublokasi,
                barang.nama as nama_barang,
                barang.satuan as satuan,
                SUM(ls.qty) as total
            FROM aktivitas as act
            INNER JOIN stok AS st
            ON st.id_aktivitas = act.id
            INNER JOIN log_stok AS ls
            ON st.id = ls.id_stok
            INNER JOIN lokasi
            ON act.id_lokasi = lokasi.id
            INNER JOIN sub_lokasi as sub
            ON act.id_sub_lokasi = sub.id
            INNER JOIN barang
            ON ls.id_barang = barang.id
            WHERE act.status = 'done'";
        
        if ($lokasi) {
            $query .= " AND act.id_lokasi = ?";
            array_push($filter, $lokasi);
        }

        if (count($sublokasi) > 0) {
            $query .= " AND act.id_sub_lokasi IN ($tsublokasi)";
            array_push($filter, ...$sublokasi);
        }

        array_push($filter, ...[$startDate, $endDate]);

        $query .= " AND act.tanggal_berangkat >= ?
                AND act.tanggal_berangkat <= ?";

        if (count($barang) > 0) {
            $query .= " AND ls.id_barang IN ($tbarang)";
            array_push($filter, ...$barang);
        }

        $query .= " GROUP BY 
                act.id_lokasi,
                act.id_sub_lokasi,
                ls.id_barang,
                ls.is_new,
                lokasi.nama,
                sub.nama,
                barang.nama,
                barang.satuan
            ";

        $data = DB::select(
            $query,
            $filter
        );

        if ($request->export == 'excel') {
            try {
                return Excel::download(new PenggunaanBarangExport($data, [$startDate, $endDate]), 'report-penggunaan-barang.xlsx');
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    protected function reportAktivitasKaryawan($request, $startDate, $endDate)
    {
        $karyawan = $request->teknisi ? $request->teknisi : [];

        $tkaryawan = implode(',', array_fill(0, count($karyawan), '?'));

        $filter = [];

        $query = "SELECT 
                act.id,
                act.no_referensi,
                act.tanggal_berangkat,
                act.tanggal_pulang,
                act.deskripsi,
                lokasi.nama as nama_lokasi,
                sub.nama as nama_sublokasi,
                karyawan.nama as nama_karyawan
            FROM aktivitas AS act
            INNER JOIN aktivitas_karyawan AS k
            ON act.id = k.id_aktivitas
            INNER JOIN karyawan
            ON karyawan.id = k.id_karyawan
            INNER JOIN lokasi
            ON act.id_lokasi = lokasi.id
            INNER JOIN sub_lokasi AS sub
            ON act.id_sub_lokasi = sub.id
            WHERE act.status = 'done'";
        
        if ($karyawan) {
            $query .= " AND k.id_karyawan IN ($tkaryawan)";
            array_push($filter, ...$karyawan);
        }

        array_push($filter, ...[$startDate, $endDate]);

        $query .= " AND act.tanggal_berangkat >= ?
                AND act.tanggal_berangkat <= ?
                ORDER BY act.tanggal_berangkat";

        $dataMain = DB::select(
            $query,
            $filter
        );

        if ($request->export == 'excel') {
            try {
                return Excel::download(new AktivitasKaryawanExport($dataMain, [$startDate, $endDate]), 'report-aktivitas-karyawan.xlsx');
            } catch (\Throwable $th) {
                throw $th;
            }
        } 

        
    }


}
