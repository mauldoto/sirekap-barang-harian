<?php

namespace App\Http\Controllers;

use App\Exports\AktivitasExport;
use App\Exports\AktivitasKaryawanExport;
use App\Exports\PenggunaanBarangExport;
use App\Exports\StokExport;
use App\Models\Aktivitas;
use App\Models\AktivitasKaryawan;
use App\Models\AlokasiDevice;
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


class AlokasiController extends Controller
{
    public function index(Request $request)
    {
        $lokasi = $request->lokasi;
        $sublokasi = $request->sublokasi;
        $alokasiDevice = AlokasiDevice::select('id_barang', DB::raw('SUM(qty) as sumqty'))
            ->with('barang')
            ->where('id_sublokasi', $sublokasi)
            ->groupBy('id_barang')
            ->get();

        $daftarLokasi = Lokasi::get();
        $daftarSublokasi = SubLokasi::where('id_lokasi', $lokasi)->get();
        $devices = Barang::get();
        $selectedSublokasi = SubLokasi::where('id', $sublokasi)->first();

        return view('contents.alokasi.index', compact('selectedSublokasi', 'lokasi', 'sublokasi', 'alokasiDevice', 'daftarLokasi', 'daftarSublokasi', 'devices'));
    }

    public function processAlokasi(Request $request)
    {
        $sublokasi = SubLokasi::where('id', $request->sublokasi)->first();

        DB::beginTransaction();

        $idsOldBarang = array_map(function ($value) {
            return $value['barang'];
        }, ($request->input ?? []));

        AlokasiDevice::where('id_sublokasi', $sublokasi->id)->whereNotIn('id_barang', $idsOldBarang)->delete();

        if ($request->barang[0]['item']) {
            foreach ($request->barang as $key => $barang) {

                if ($barang['qty'] == '' || $barang['qty'] < 1) {
                    DB::rollBack();
                    return back()->withErrors(['Error input log stok, QTY harus diisi dan minimal 1.'])->withInput();
                }

                $alokasidevice = new AlokasiDevice();
                $alokasidevice->id_sublokasi = $sublokasi->id;
                $alokasidevice->id_barang = $barang['item'];
                $alokasidevice->qty = $barang['qty'];

                if (!$alokasidevice->save()) {
                    DB::rollBack();
                    return back()->withErrors(['Error input log stok.'])->withInput();
                }
            }
        }

        DB::commit();
        return back()->with(['success' => 'Update stok keluar berhasil. [' . $sublokasi->nama . ']']);
    }
}
