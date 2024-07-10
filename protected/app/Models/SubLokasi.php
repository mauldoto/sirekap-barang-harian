<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLokasi extends Model
{
    use HasFactory;

    protected $table = 'sub_lokasi';

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'input_by', 'id');
    }
}
