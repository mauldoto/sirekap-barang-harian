<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Akomodasi;
use App\Models\Aktivitas;
use App\Models\LogAkomodasi;

class AkomodasiController extends Controller
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

        $akomodasi = Akomodasi::with(['user'])
            ->where('tanggal_terbit', '>=', $startDate)
            ->where('tanggal_terbit', '<=', $endDate)
            ->get();
        
        // $reportAktivitas = Akomodasi::orderBy('tanggal_terbit', 'DESC')->get();

        return view('contents.akomodasi.index', compact('akomodasi', 'startDate', 'endDate'));
    }

    public function getDetail(request $request, $noref)
    {
        $akm = Akomodasi::where('no_referensi', $noref)->with(['aktivitas'])->first();

        if (!$akm) {
            return;
        }

        return response()->json([
            'status' => 'success',
            'data'   => $akm
        ]);
    }

    public function inputView(Request $request)
    {
        $dateNow = Carbon::now()->format('Y-m-d');
        $aktivitas = Aktivitas::whereIn('status', ['waiting', 'progress'])
            ->doesntHave('akomodasi')
            ->with(['lokasi', 'sublokasi'])
            ->get();

        // dd($dateNow);
        return view('contents.akomodasi.input', compact('aktivitas', 'dateNow'));
    }

    public function inputAkomodasi(Request $request)
    {
        // validasi
        $validator = Validator::make($request->all(), [
            'noref'             => 'required',
            'tanggal'           => 'required|date',
            'nominal_pengajuan' => 'required|numeric',
            'nominal_realisasi' => 'required|numeric',
            'keterangan'        => 'nullable',
            'aktivitas'         => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        $newAkomodasi = new Akomodasi();
        $newAkomodasi->no_referensi = $request->noref;
        $newAkomodasi->tanggal_terbit = $request->tanggal;
        $newAkomodasi->nominal_pengajuan = $request->nominal_pengajuan;
        $newAkomodasi->nominal_realisasi = $request->nominal_realisasi;
        $newAkomodasi->id_pemohon = $request->pemohon;
        $newAkomodasi->input_by = $request->user()->id;
        $newAkomodasi->deskripsi = $request->keterangan;

        if (!$newAkomodasi->save()) {
            DB::rollback();
            return back()->withErrors(['Gagal input akomodasi.']);
        }

        foreach ($request->aktivitas as $key => $aktv) {
            $newRel = new LogAkomodasi();
            $newRel->id_akomodasi = $newAkomodasi->id;
            $newRel->id_aktivitas = $aktv;

            if (!$newRel->save()) {
                DB::rollback();
                return back()->withErrors(['Gagal link akomodasi dan aktivitas.']);
            }
        }

        DB::commit();
        return back()->with(['success' => 'Input akomodasi berhasil.']);
    }

    public function editView(Request $request, $noref)
    {
        $akomodasi = Akomodasi::where('no_referensi', $noref)->with(['aktivitas' => ['lokasi', 'sublokasi']])->first();

        $aktivitas = Aktivitas::whereIn('status', ['waiting', 'progress'])
            ->doesntHave('akomodasi')
            ->with(['lokasi', 'sublokasi'])
            ->get();

        // dd($dateNow);
        return view('contents.akomodasi.edit', compact('akomodasi', 'aktivitas'));
    }

    public function editAkomodasi(Request $request, $noref)
    {
        // validasi
        $validator = Validator::make($request->all(), [
            // 'noref'             => 'required',
            'tanggal'           => 'required|date',
            'nominal_pengajuan' => 'required|numeric',
            'nominal_realisasi' => 'required|numeric',
            'keterangan'        => 'nullable',
            'aktivitas'         => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $akomodasi = Akomodasi::where('no_referensi', $noref)->first();
        if (!$akomodasi) {
            return back()->withErrors(['Data akomodasi tidak ditemukan atau sudah dihapus.']);
        }

        DB::beginTransaction();

        $akomodasi->tanggal_terbit = $request->tanggal;
        $akomodasi->nominal_pengajuan = $request->nominal_pengajuan;
        $akomodasi->nominal_realisasi = $request->nominal_realisasi;
        $akomodasi->id_pemohon = $request->pemohon;
        $akomodasi->input_by = $request->user()->id;
        $akomodasi->deskripsi = $request->keterangan;

        if (!$akomodasi->save()) {
            DB::rollback();
            return back()->withErrors(['Gagal update data akomodasi.']);
        }

        $deleteAkm = LogAkomodasi::where('id_akomodasi', $akomodasi->id)->delete();
        if (!$deleteAkm) {
            DB::rollback();
            return back()->withErrors(['Gagal update data akomodasi pada bagian link akomodasi dan aktivitas.']);
        }

        foreach ($request->aktivitas as $key => $aktv) {
            $newRel = new LogAkomodasi();
            $newRel->id_akomodasi = $akomodasi->id;
            $newRel->id_aktivitas = $aktv;

            if (!$newRel->save()) {
                DB::rollback();
                return back()->withErrors(['Gagal link akomodasi dan aktivitas.']);
            }
        }

        DB::commit();
        return back()->with(['success' => 'Update data akomodasi berhasil.']);
    }
}
