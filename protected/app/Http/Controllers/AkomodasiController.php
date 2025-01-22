<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Akomodasi;

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

        $akomodasi = Akomodasi::where('tanggal_terbit', '>=', $startDate)
            ->where('tanggal_terbit', '<=', $endDate)
            ->get();

        // $reportAktivitas = Akomodasi::orderBy('tanggal_terbit', 'DESC')->get();

        return view('contents.akomodasi.index', compact('akomodasi', 'startDate', 'endDate'));
    }
}
