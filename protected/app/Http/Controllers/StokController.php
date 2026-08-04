<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Karyawan;
use App\Models\LogStok;
use App\Models\Lokasi;
use App\Models\Stok;
use App\Models\SubLokasi;
use App\Models\TempCart;
use Carbon\Carbon;
use Error;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    public function index()
    {
        $stok = LogStok::select('id_barang', 'id_gudang', DB::raw('SUM(qty) as sumqty'), 'is_new')
            ->with('barang', 'gudang')
            ->groupBy('id_barang', 'is_new', 'id_gudang')->get();

        return view('contents.stok.index', compact('stok'));
    }

    public function listTransaction(Request $request)
    {
        $startDate = $request->dari ? Carbon::createFromFormat('Y-m-d', $request->dari)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $type = $request->filter_type;

        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $stok = Stok::where('tanggal', '>=', $startDate)
            ->where('tanggal', '<=', $endDate)
            ->with('aktivitas', 'user');

        if ($type) {
            $stok = $stok->where('type', $type);
        }

        $stok = $stok->get();

        return view('contents.stok.list-transaksi', compact('stok', 'startDate', 'endDate', 'type'));
    }

    public function detailTransaksi($noref)
    {
        $stok = Stok::where('no_referensi', $noref)
            ->with(['user', 'aktivitas' => ['lokasi', 'sublokasi']])
            ->firstOrFail();

        $items = LogStok::where('id_stok', $stok->id)
            ->with(['barang', 'gudang'])
            ->get();

        return view('contents.stok.detail-transaksi', compact('stok', 'items'));
    }

    public function log(Request $request)
    {
        $startDate = $request->dari ? Carbon::createFromFormat('Y-m-d', $request->dari)->format('Y-m-d') : Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $request->ke ? Carbon::createFromFormat('Y-m-d', $request->ke)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $type = $request->filter_type;
        $barang = Barang::all();

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

        $stok = LogStok::whereIn('id_stok', $stokM)->with(['barang', 'gudang', 'stok' => ['user', 'aktivitas']])->get();

        return view('contents.stok.log', compact('stok', 'startDate', 'endDate', 'type', 'barang'));
    }

    public function viewStokMasuk()
    {
        $gudang = Gudang::all();
        $barang = Barang::all();
        return view('contents.stok.stokin', compact('barang', 'gudang'));
    }

    public function storeStokMasuk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noref' => 'required|string',
            'tanggal' => 'required|date',
            'barang' => 'required|array',
            'barang.*.item' => 'required',
            'barang.*.qty' => 'required',
            'barang.*.bekas' => 'nullable',
            'barang.*.gudang' => 'required',
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
            $newLogStok->id_gudang = $barang['gudang'];
            $newLogStok->is_new = array_key_exists('bekas', $barang) ? false : true;
            $newLogStok->harga = str_replace('.', '', $barang['price']);

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
        $gudang = Gudang::all();
        $aktivitas = Aktivitas::whereIn('status', ['waiting', 'progress'])->orderBy('tanggal_berangkat', 'DESC')->get();
        return view('contents.stok.stokout', compact('gudang', 'aktivitas'));
    }

    public function getItemWithStock($idgudang, $level = 2)
    {
        // level 1 take all item
        // level 2 take item with qty > 0
        try {
            $stok = LogStok::select('id_barang', DB::raw('SUM(qty) as sumqty'), 'is_new')
                ->where('id_gudang', $idgudang)
                ->with('barang');
            if ($level == 2)
                $stok = $stok->having('sumqty', '>', 0);
            $stok = $stok->groupBy('id_barang', 'is_new', 'id_gudang')->get();

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
            };

            if (count($barang) <= 0)
                throw new Error('Tidak ada stok di gudang ini.');

            return response()->json([
                'data' => $barang
            ]);
        } catch (\Throwable $th) {
            throw $th->getMessage($th);
        }
    }

    public function storeStokKeluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noref' => 'required|string',
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable',
            'barang' => 'required|array',
            'barang.*.item' => 'required',
            'barang.*.qty' => 'required',
            'barang.*.bekas' => 'nullable',
            'barang.*.gudang' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        $idsBarang = array_map(function ($value) {
            return $value['item'];
        }, $request->barang);

        // dd($idsBarang);
        $stokLogs = LogStok::whereIn('id_barang', $idsBarang)->select('id_barang', 'id_gudang', 'is_new', 'harga', DB::raw('SUM(qty) as sumqty'))->groupBy('id_barang', 'is_new', 'id_gudang', 'harga')->get()->toArray();

        $newStokOut = new Stok();
        $newStokOut->no_referensi = $request->noref;
        $newStokOut->id_aktivitas = null;
        $newStokOut->tanggal = $request->tanggal;
        $newStokOut->type = 'keluar';
        $newStokOut->deskripsi = $request->deskripsi;
        $newStokOut->input_by = $request->user()->id;

        if (!$newStokOut->save()) {
            DB::rollBack();
            return back()->withErrors(['Input stok keluar gagal.'])->withInput();
        }

        foreach ($request->barang as $key => $barang) {

            $checkStok = array_filter($stokLogs, function ($value) use ($barang) {
                if ($value['id_gudang'] == $barang['gudang'] && $value['id_barang'] == $barang['item'] && $value['is_new'] == !array_key_exists('bekas', $barang) && $value['sumqty'] >= $barang['qty']) {
                    return $value;
                } else {
                    return false;
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
            $newLogStok->id_gudang = $barang['gudang'];
            $newLogStok->is_new = array_key_exists('bekas', $barang) ? false : true;
            $newLogStok->harga = str_replace('.', '', $checkStok[0]['harga']);

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
        $gudang = Gudang::all();
        $lokasi = Lokasi::all();
        $karyawan = Karyawan::all();
        $tiket = Aktivitas::with('lokasi', 'sublokasi')->whereNotIn('status', ['done', 'cancel'])->get();

        return view('contents.stok.rencana', compact('gudang', 'lokasi', 'karyawan', 'tiket'));
    }

    public function storeRencanaSK(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tiket' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $tiket = Aktivitas::with('lokasi', 'sublokasi')->whereNotIn('status', ['done', 'cancel'])->get()->toArray();
        $dbBarang = Barang::get()->toArray();
        $dbGudang = Gudang::get()->toArray();

        $data = [];
        foreach ($request->selected_tiket as $key => $tiketSelected) {
            $dataSub = array_values(array_filter($tiket, function ($item) use ($tiketSelected) {
                if ($item['no_referensi'] == $tiketSelected) {
                    return $item;
                }
            }))[0];

            if (count($dataSub) <= 0) {
                continue;
            }

            $data[$tiketSelected] = $dataSub;

            $barangIds = array_unique(array_column($request['tiket_' . $tiketSelected], 'item'));
            $barangSelected = array_filter($dbBarang, function ($item) use ($barangIds) {
                if (in_array($item['id'], $barangIds)) {
                    return $item;
                }
            });

            $barangFinal = [];
            foreach ($request['tiket_' . $tiketSelected] as $key => $barang) {
                foreach ($barangSelected as $key => $dbb) {
                    if ($barang['item'] == $dbb['id']) {
                        $dbb['kondisi'] = array_key_exists('bekas', $barang) ? 'Bekas' : 'Baru';
                        $dbb['qty'] = $barang['qty'];
                        foreach ($dbGudang as $key => $gudang) {
                            if ($barang['gudang'] == $gudang['id']) {
                                $dbb['gudang'] = $gudang['nama'];
                                $dbb['gudang_id'] = $gudang['id'];
                            }
                        }

                        array_push($barangFinal, $dbb);
                    }
                }
            }

            $data[$tiketSelected]['barang'] = $barangFinal;
        }


        DB::beginTransaction();

        foreach ($data as $key => $dataInput) {
            foreach ($dataInput['barang'] as $key => $barang) {
                $newTempCart = new TempCart();
                $newTempCart->id_aktivitas = $dataInput['id'];
                $newTempCart->id_barang = $barang['id'];
                $newTempCart->qty = $barang['qty'];
                $newTempCart->id_gudang = $barang['gudang_id'];
                $newTempCart->is_new = $barang['kondisi'] == 'Bekas' ? false : true;
                $newTempCart->harga = $barang['kondisi'] == 'Bekas' ? $barang['h_second'] : $barang['h_new'];

                if (!$newTempCart->save()) {
                    DB::rollBack();
                    return back()->withErrors(['Error input temp cart.'])->withInput();
                }
            }
        }

        DB::commit();

        $pdf = LaravelMpdf::loadview('exports.pdf.cetak-pengajuan', [
            'barang' => $data
        ]);

        return $pdf->stream('pengajuan-stok-' . date('dmY') . '.pdf');
        // return redirect()->route('stok.rencana')->with(['success' => 'Input temp cart berhasil.']);
    }

    public function cetakRencanaSK(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'lokasi' => 'required',
            'sublokasi' => 'required|array',
            'teknisi' => 'required',
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
        $dbGudang = Gudang::get()->toArray();

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
                        foreach ($dbGudang as $key => $gudang) {
                            if ($barang['gudang'] == $gudang['id']) {
                                $dbb['gudang'] = $gudang['nama'];
                            }
                        }

                        array_push($barangFinal, $dbb);
                    }
                }
            }

            $data[$subSelected]['barang'] = $barangFinal;
        }

        $pdf = LaravelMpdf::loadview('exports.pdf.cetak-perencanaan', [
            'tanggal' => Carbon::createFromFormat('Y-m-d', $request->tanggal)->format('d-m-Y'),
            'lokasi' => $lokasi,
            'karyawan' => $karyawan,
            'barang' => $data
        ]);

        return $pdf->stream('pengeluaran-stok-' . $lokasi->nama . '-' . date('dmY') . '.pdf');
    }

    public function logupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_item' => 'required',
            'bekas' => 'nullable',
            'qty' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $upOrCreate = DB::table('log_stok')
            ->updateOrInsert(
                ['id_stok' => $request->stok, 'id_barang' => $request->item],
                [
                    'id_stok' => $request->stok,
                    'id_barang' => $request->item,
                    'is_new' => $request->bekas ? false : true,
                    'qty' => $request->qty
                ]
            );

        if (!$upOrCreate) {
            return back()->withErrors(['Gagal update stok log.']);
        }

        return back()->with(['success', 'Update stok log berhasil']);
    }


    public function logdelete(Request $request)
    {
        $dataDeleted = DB::table('log_stok')
            ->where(
                ['id_stok' => $request->delete_stok, 'id_barang' => $request->delete_item]
            )->delete();

        if (!$dataDeleted) {
            return back()->withErrors(['Gagal hapus stok log.']);
        }

        return back()->with(['success', 'Hapus stok log berhasil']);
    }

    public function invoice($noref)
    {
        $stok = Stok::where('no_referensi', $noref)
            ->with(['user', 'aktivitas' => ['lokasi', 'sublokasi']])
            ->firstOrFail();

        $items = LogStok::where('id_stok', $stok->id)
            ->with(['barang', 'gudang'])
            ->get();

        return view('contents.stok.invoice', compact('stok', 'items'));
    }

    public function printInvoice($noref)
    {
        $stok = Stok::where('no_referensi', $noref)
            ->with(['user', 'aktivitas' => ['lokasi', 'sublokasi']])
            ->firstOrFail();

        $items = LogStok::where('id_stok', $stok->id)
            ->with(['barang', 'gudang'])
            ->get();

        $pdf = LaravelMpdf::loadview('exports.pdf.invoice-stok', [
            'stok' => $stok,
            'items' => $items,
            'tanggal' => Carbon::now()->format('d-m-Y H:i')
        ], [], [
            'format' => 'A4-L',
            'orientation' => 'L'
        ]);

        return $pdf->stream('invoice-' . $noref . '.pdf');
    }


    public function viewRetur($noref)
    {
        $StokOut = Stok::where('no_referensi', $noref)->first();
        if (!$StokOut) {
            return back()->withErrors(['Data stok keluar tidak ditemukan.']);
        }

        $stockLogs = LogStok::select('id_barang', 'id_gudang', DB::raw('SUM(qty) as sumqty'), 'is_new', 'harga')
            ->where('id_stok', $StokOut->id)
            ->with('barang', 'gudang')
            ->groupBy('id_barang', 'is_new', 'id_gudang', 'harga')
            ->get();

        return view('contents.stok.retur', compact('StokOut', 'stockLogs'));
    }

    public function storeRetur(Request $request, $noref)
    {
        $validator = Validator::make($request->all(), [
            'input' => 'required|array',
            'input.*.barang' => 'required',
            'input.*.bekas' => 'required',
            'input.*.qty_retur' => 'required',
            'input.*.gudang' => 'required',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $StokOut = Stok::where('no_referensi', $noref)->first();
        if (!$StokOut) {
            return back()->withErrors(['Data stok keluar tidak ditemukan.']);
        }

        $stokLogs = LogStok::where('id_stok', $StokOut->id)
            ->select('id_barang', 'is_new', 'id_gudang', DB::raw('SUM(qty) as sumqty'), 'harga')
            ->groupBy('id_barang', 'is_new', 'id_gudang', 'harga')
            ->get()->toArray();

        DB::beginTransaction();

        $newRetur = new Stok();
        $newRetur->no_referensi = $request->noref;
        $newRetur->id_aktivitas = $request->id_aktivitas;
        $newRetur->id_parent = $StokOut->id;
        $newRetur->tanggal = Carbon::now()->format('Y-m-d');
        $newRetur->type = 'retur';
        $newRetur->input_by = $request->user()->id;
        $newRetur->deskripsi = 'Retur stok keluar untuk stok keluar ' . $StokOut->no_referensi;


        if (!$newRetur->save()) {
            DB::rollBack();
            return back()->withErrors(['Input retur stok gagal.'])->withInput();
        }

        foreach ($request->input as $key => $barang) {

            $checkStok = array_filter($stokLogs, function ($value) use ($barang) {
                if ($value['id_gudang'] == $barang['gudang'] && $value['id_barang'] == $barang['barang'] && $value['is_new'] == $barang['bekas'] && ($value['sumqty'] * -1) >= $barang['qty_retur']) {
                    return $value;
                } else {
                    return false;
                }
            });

            if (!$checkStok) {
                DB::rollBack();
                return back()->withErrors(['Error retur stok, ada barang retur melebihi stok yang dikeluarkan.'])->withInput();
            }

            $newLogStok = new LogStok();
            $newLogStok->id_stok = $newRetur->id;
            $newLogStok->id_barang = $checkStok[0]['id_barang'];
            $newLogStok->qty = $barang['qty_retur'] > 0 ? $barang['qty_retur'] : 0;
            $newLogStok->id_gudang = $checkStok[0]['id_gudang'];
            $newLogStok->is_new = $checkStok[0]['is_new'];
            $newLogStok->harga = $checkStok[0]['harga'];

            if (!$newLogStok->save()) {
                DB::rollBack();
                return back()->withErrors(['Error input log stok untuk retur.'])->withInput();
            }
        }

        // if ($StokOut->id_aktivitas) {
        //     TempCart::where('id_aktivitas', $StokOut->id_aktivitas)->delete();
        //     foreach ($request->barang as $key => $barang) {

        //         $checkStok = array_filter($stokLogs, function ($value) use ($barang) {
        //             if ($value['id_gudang'] == $barang['gudang'] && $value['id_gudang'] == $barang['gudang'] && $value['id_barang'] == $barang['barang'] && $value['is_new'] == !array_key_exists('bekas', $barang) && $value['sumqty'] >= $barang['qty_retur']) {
        //                 return $value;
        //             } else {
        //                 return false;
        //             }
        //         });

        //         if (!$checkStok) {
        //             DB::rollBack();
        //             return back()->withErrors(['Error retur stok, ada barang retur melebihi stok yang dikeluarkan.'])->withInput();
        //         }

        //         $newTempStok = new TempCart();
        //         $newTempStok->id_aktivitas = $StokOut->id_aktivitas;
        //         $newTempStok->id_barang = $checkStok[0]['id_barang'];
        //         $newTempStok->qty = $checkStok[0]['sumqty'];
        //         $newTempStok->qty_used = $checkStok[0]['sumqty'] - $barang['qty_retur'];
        //         $newTempStok->id_gudang = $checkStok[0]['id_gudang'];
        //         $newTempStok->is_new = $checkStok[0]['is_new'];
        //         $newTempStok->harga = $checkStok[0]['harga'];

        //         if (!$newTempStok->save()) {
        //             DB::rollBack();
        //             return back()->withErrors(['Error input barang terpakai setelah input retur.'])->withInput();
        //         }
        //     }
        // }

        DB::commit();
        return redirect()->route('stok.transaksi')->with(['success' => 'Input retur stok berhasil.']);
    }

    public function editRetur($noref)
    {
        $Retur = Stok::where('no_referensi', $noref)->where('type', 'retur')->first();
        if (!$Retur) {
            return back()->withErrors(['Data retur tidak ditemukan.']);
        }

        $StokOut = Stok::where('id', $Retur->id_parent)->first();
        if (!$StokOut) {
            return back()->withErrors(['Data stok keluar tidak ditemukan.']);
        }

        $stockLogs = LogStok::select('id_barang', 'id_gudang', DB::raw('SUM(qty) as sumqty'), 'is_new', 'harga')
            ->where('id_stok', $StokOut->id)
            ->with('barang', 'gudang')
            ->groupBy('id_barang', 'is_new', 'id_gudang', 'harga')
            ->get();

        $returLogs = LogStok::where('id_stok', $Retur->id)->get()->keyBy(function ($item) {
            return $item->id_barang . '-' . $item->is_new . '-' . $item->id_gudang;
        });

        return view('contents.stok.edit-retur', compact('StokOut', 'Retur', 'stockLogs', 'returLogs'));
    }

    public function updateRetur(Request $request, $noref)
    {
        $validator = Validator::make($request->all(), [
            'barang' => 'required|array',
            'barang.*.item' => 'required',
            'barang.*.qty_retur' => 'required',
            'barang.*.gudang' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $Retur = Stok::where('no_referensi', $noref)->where('type', 'retur')->first();
        if (!$Retur) {
            return back()->withErrors(['Data retur tidak ditemukan.']);
        }

        $StokOut = Stok::where('id', $Retur->id_parent)->first();
        if (!$StokOut) {
            return back()->withErrors(['Data stok keluar tidak ditemukan.']);
        }

        $stokLogs = LogStok::where('id_stok', $StokOut->id)
            ->select('id_barang', 'is_new', 'id_gudang', DB::raw('SUM(qty) as sumqty'), 'harga')
            ->groupBy('id_barang', 'is_new', 'id_gudang', 'harga')
            ->get()->toArray();

        DB::beginTransaction();

        $Retur->input_by = $request->user()->id;
        if (!$Retur->save()) {
            DB::rollBack();
            return back()->withErrors(['Update retur stok gagal.'])->withInput();
        }

        // Delete old LogStok for this retur to replace them
        LogStok::where('id_stok', $Retur->id)->delete();

        foreach ($request->barang as $key => $barang) {
            if (!isset($barang['qty_retur']) || $barang['qty_retur'] == '' || $barang['qty_retur'] < 0) {
                $barang['qty_retur'] = 0;
            }

            if ($barang['qty_retur'] > 0) {
                $checkStok = array_filter($stokLogs, function ($value) use ($barang) {
                    if ($value['id_gudang'] == $barang['gudang'] && $value['id_barang'] == $barang['item'] && $value['is_new'] == $barang['bekas']) {
                        // compare absolute values since StokOut qty is negative
                        if (abs($value['sumqty']) >= $barang['qty_retur']) {
                            return $value;
                        }
                    }
                    return false;
                });

                if (!$checkStok) {
                    DB::rollBack();
                    return back()->withErrors(['Error retur stok, ada barang retur melebihi stok yang dikeluarkan.'])->withInput();
                }

                $checkStok = reset($checkStok); // get first element

                $newLogStok = new LogStok();
                $newLogStok->id_stok = $Retur->id;
                $newLogStok->id_barang = $checkStok['id_barang'];
                $newLogStok->qty = $barang['qty_retur'];
                $newLogStok->id_gudang = $checkStok['id_gudang'];
                $newLogStok->is_new = $checkStok['is_new'];
                $newLogStok->harga = $checkStok['harga'];

                if (!$newLogStok->save()) {
                    DB::rollBack();
                    return back()->withErrors(['Error update log stok untuk retur.'])->withInput();
                }
            }
        }

        if ($StokOut->id_aktivitas) {
            TempCart::where('id_aktivitas', $StokOut->id_aktivitas)->delete();

            // Need to recalculate all TempCart based on StokOut minus total Retur for this StokOut
            foreach ($request->barang as $key => $barang) {
                $qty_retur = isset($barang['qty_retur']) && $barang['qty_retur'] >= 0 ? $barang['qty_retur'] : 0;

                $checkStok = array_filter($stokLogs, function ($value) use ($barang) {
                    if ($value['id_gudang'] == $barang['gudang'] && $value['id_barang'] == $barang['item'] && $value['is_new'] == !array_key_exists('bekas', $barang) && abs($value['sumqty']) >= (isset($barang['qty_retur']) ? $barang['qty_retur'] : 0)) {
                        return $value;
                    }
                    return false;
                });

                if ($checkStok) {
                    $checkStok = reset($checkStok);
                    $newTempStok = new TempCart();
                    $newTempStok->id_aktivitas = $StokOut->id_aktivitas;
                    $newTempStok->id_barang = $checkStok['id_barang'];
                    $newTempStok->qty = abs($checkStok['sumqty']);
                    $newTempStok->qty_used = abs($checkStok['sumqty']) - $qty_retur;
                    $newTempStok->id_gudang = $checkStok['id_gudang'];
                    $newTempStok->is_new = $checkStok['is_new'];
                    $newTempStok->harga = $checkStok['harga'];

                    if (!$newTempStok->save()) {
                        DB::rollBack();
                        return back()->withErrors(['Error input barang terpakai setelah update retur.'])->withInput();
                    }
                }
            }
        }

        DB::commit();
        return redirect()->route('stok.transaksi')->with(['success' => 'Update retur stok berhasil.']);
    }

    // -------------------------------------------------------
    // Koreksi Stok
    // -------------------------------------------------------
    public function viewStokKoreksi()
    {
        $gudang = Gudang::all();
        $barang = Barang::all();
        return view('contents.stok.stokkoreksi', compact('barang', 'gudang'));
    }

    public function storeStokKoreksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noref'            => 'required|string',
            'tanggal'          => 'required|date',
            'keterangan'       => 'nullable|string',
            'barang'           => 'required|array',
            'barang.*.item'    => 'required',
            'barang.*.qty'     => 'required|numeric|min:1',
            'barang.*.gudang'  => 'required',
            'barang.*.aksi'    => 'required|in:tambah,kurangi',
            'barang.*.bekas'   => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        // Validate that 'kurangi' items won't result in negative stock
        $idsBarang = array_column($request->barang, 'item');
        $stokLogs  = LogStok::whereIn('id_barang', $idsBarang)
            ->select('id_barang', 'id_gudang', 'is_new', DB::raw('SUM(qty) as sumqty'))
            ->groupBy('id_barang', 'is_new', 'id_gudang')
            ->get()
            ->toArray();

        foreach ($request->barang as $barang) {
            if ($barang['aksi'] === 'kurangi') {
                $isNew = !array_key_exists('bekas', $barang);

                $checkStok = array_filter($stokLogs, function ($value) use ($barang, $isNew) {
                    return $value['id_gudang'] == $barang['gudang']
                        && $value['id_barang']  == $barang['item']
                        && $value['is_new']     == $isNew
                        && $value['sumqty']     >= $barang['qty'];
                });

                if (!$checkStok) {
                    DB::rollBack();
                    return back()->withErrors([
                        'Koreksi gagal: salah satu barang akan menghasilkan stok minus.'
                    ])->withInput();
                }
            }
        }

        $newKoreksi               = new Stok();
        $newKoreksi->no_referensi = $request->noref;
        $newKoreksi->tanggal      = $request->tanggal;
        $newKoreksi->type         = 'koreksi';
        $newKoreksi->input_by     = $request->user()->id;
        $newKoreksi->deskripsi    = $request->keterangan;

        if (!$newKoreksi->save()) {
            DB::rollBack();
            return back()->withErrors(['Input koreksi stok gagal.'])->withInput();
        }

        foreach ($request->barang as $barang) {
            $qty = (int) $barang['qty'];
            // 'tambah' = positive, 'kurangi' = negative
            if ($barang['aksi'] === 'kurangi') {
                $qty = -$qty;
            }

            $newLogStok           = new LogStok();
            $newLogStok->id_stok  = $newKoreksi->id;
            $newLogStok->id_barang = $barang['item'];
            $newLogStok->qty      = $qty;
            $newLogStok->id_gudang = $barang['gudang'];
            $newLogStok->is_new   = array_key_exists('bekas', $barang) ? false : true;
            $newLogStok->harga    = isset($barang['price']) ? str_replace('.', '', $barang['price']) : 0;

            if (!$newLogStok->save()) {
                DB::rollBack();
                return back()->withErrors(['Error input log koreksi stok.'])->withInput();
            }
        }

        DB::commit();
        return back()->with(['success' => 'Input koreksi stok berhasil.']);
    }
}
