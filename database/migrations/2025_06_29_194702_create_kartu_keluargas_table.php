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
        Schema::create('kartu_keluargas', function (Blueprint $table) {
            $table->id(); // Auto increment ID (PK)
            $table->string('no_kk'); // Ini tetap disimpan, tapi bukan PK
            $table->string('nama_kepala_keluarga');
            $table->string('alamat_jalan');
            $table->string('rt');
            $table->string('rw');
            $table->string('kode_pos');
            $table->string('telp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluargas');
    }
};
