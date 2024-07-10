<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    use HasFactory;

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id');
    }

    public function sublokasi()
    {
        return $this->belongsTo(SubLokasi::class, 'id_sub_lokasi', 'id');
    }

    public function teknisi()
    {
        return $this->hasMany(AktivitasKaryawan::class, 'id_aktivitas', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'input_by', 'id');
    }
}
