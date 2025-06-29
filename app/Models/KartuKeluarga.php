<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KartuKeluarga extends Model
{
    protected $fillable = [
        'no_kk',
        'nama_kepala_keluarga',
        'alamat_jalan',
        'rt',
        'rw',
        'kode_pos',
        'telp',
    ];
}
