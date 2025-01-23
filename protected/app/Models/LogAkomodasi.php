<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAkomodasi extends Model
{
    use HasFactory;

    protected $table = 'akomodasi_aktivitas';

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id_aktivitas', 'id');
    }

    public function akomodasi()
    {
        return $this->belongsTo(Akomodasi::class, 'id_akomodasi', 'id');
    }
}
