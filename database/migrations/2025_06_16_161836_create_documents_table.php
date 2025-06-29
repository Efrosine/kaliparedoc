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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('client_id')->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->foreignId('type_id')->constrained('document_types');
            $table->string('number')->nullable()->unique();
            $table->string('nik', 16);
            $table->string('kk', 16);
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
            $table->json('data_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
