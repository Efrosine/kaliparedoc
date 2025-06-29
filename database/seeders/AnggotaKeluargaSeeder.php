<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class AnggotaKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $csv = Reader::createFromPath(database_path('seeders/csv/anggota_keluarga.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            $tanggal_lahir = trim($row['tanggal_lahir'] ?? '');
            $tanggal_fix = null;

            if ($tanggal_lahir) {
                try {
                    // Coba format d/m/Y
                    $tanggal_fix = \Carbon\Carbon::createFromFormat('d/m/Y', $tanggal_lahir)->format('Y-m-d');
                } catch (\Exception $e1) {
                    try {
                        // Kalau gagal, coba format d-m-Y
                        $tanggal_fix = \Carbon\Carbon::createFromFormat('d-m-Y', $tanggal_lahir)->format('Y-m-d');
                    } catch (\Exception $e2) {
                        // Kalau dua-duanya gagal, biarin null
                        dump("Format tanggal tidak dikenali: " . $tanggal_lahir);
                        $tanggal_fix = "";
                    }
                }
            }


            DB::table('anggota_keluargas')->insert([
                'no_kk' => $row['no_kk'],
                'no_urut' => $row['no_urut'],
                'nik' => $row['nik'],
                'nama' => $row['nama'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $tanggal_fix,
                'golongan_darah' => $row['golongan_darah'],
                'agama' => $row['agama'],
                'status_perkawinan' => $row['status_perkawinan'],
                'status_hubungan_dalam_keluarga' => $row['status_hubungan_dalam_keluarga'],
                'pendidikan' => $row['pendidikan'],
                'pekerjaan' => $row['pekerjaan'],
                'nama_ibu' => $row['nama_ibu'],
                'nama_ayah' => $row['nama_ayah'],
            ]);
        }
    }
}
