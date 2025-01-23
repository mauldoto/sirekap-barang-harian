<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akomodasi extends Model
{
    use HasFactory;

    protected $table = 'akomodasi';

    public function user()
    {
        return $this->belongsTo(User::class, 'input_by', 'id');
    }

    public function aktivitas()
    {
        return $this->belongsToMany(Aktivitas::class, 'akomodasi_aktivitas', 'id_akomodasi', 'id_aktivitas');
    }
}
