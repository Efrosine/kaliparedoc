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
        Schema::create('document_type_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained()->onDelete('cascade');
            $table->integer('version');
            $table->string('name');
            $table->timestamp('created_at')->useCurrent();
            $table->foreignId('updated_by')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_type_versions');
    }
};
