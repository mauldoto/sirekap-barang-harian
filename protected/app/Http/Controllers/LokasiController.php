<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\SubLokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();
        $sublokasi = SubLokasi::all();
        return view('contents.lokasi.index', compact('lokasi', 'sublokasi'));
    }

    public function store(Request $request)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function delete(Request $request, $id)
    {
    }
}
