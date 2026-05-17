<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempCart extends Model
{
    use HasFactory;

    protected $table = 'temp_carts';
    protected $fillable = [
        'id_aktivitas',
        'id_barang',
        'qty',
        'qty_used',
        'id_gudang',
        'is_new',
    ];

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id_aktivitas', 'id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang', 'id');
    }
}
