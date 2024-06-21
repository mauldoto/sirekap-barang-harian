<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::all();
        return view('contents.karyawan.index', compact('karyawan'));
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
