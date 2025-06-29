<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class KartuKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $csv = Reader::createFromPath(database_path('seeders/csv/kartu_keluarga.csv'), 'r');
        $csv->setHeaderOffset(0); // baris pertama sebagai header

        foreach ($csv as $row) {
            DB::table('kartu_keluargas')->insert([
                'no_kk' => $row['no_kk'],
                'nama_kepala_keluarga' => $row['nama_kepala_keluarga'],
                'alamat_jalan' => $row['alamat_jalan'],
                'rt' => $row['rt'],
                'rw' => $row['rw'],
                'kode_pos' => $row['kode_pos'],
                'telp' => $row['telp'],
            ]);
        }
    }
}
