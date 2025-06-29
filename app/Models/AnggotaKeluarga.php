<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    protected $fillable = [
        'no_kk',
        'no_urut',
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'golongan_darah',
        'agama',
        'status_perkawinan',
        'status_hubungan_dalam_keluarga',
        'pendidikan',
        'pekerjaan',
        'nama_ibu',
        'nama_ayah',
    ];
}
