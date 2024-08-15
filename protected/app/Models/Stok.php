<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    use HasFactory;

    protected $table = 'stok';

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'id_aktivitas', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'input_by', 'id');
    }

    public function stok()
    {
        return $this->hasMany(LogStok::class, 'id_stok', 'id');
    }
}
