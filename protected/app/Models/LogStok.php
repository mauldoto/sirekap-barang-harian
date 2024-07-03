<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogStok extends Model
{
    use HasFactory;

    protected $table = 'log_stok';

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function stok()
    {
        return $this->belongsTo(Stok::class, 'id_stok', 'id');
    }
}
