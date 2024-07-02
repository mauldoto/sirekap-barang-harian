<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index()
    {
        return view('contents.aktivitas.index');
    }

    public function input()
    {
        $karyawan = Karyawan::all();
        $lokasi = Lokasi::all();
        return view('contents.aktivitas.input', compact('karyawan', 'lokasi'));
    }

    public function store()
    {
        
    }
}
