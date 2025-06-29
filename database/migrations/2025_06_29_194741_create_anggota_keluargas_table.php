<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota_keluargas', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk');
            $table->integer('no_urut');
            $table->string('nik');
            $table->string('nama');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('golongan_darah')->nullable();
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('status_hubungan_dalam_keluarga');
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_keluargas');
    }
};
