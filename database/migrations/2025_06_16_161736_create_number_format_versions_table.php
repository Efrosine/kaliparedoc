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
        Schema::create('number_format_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('number_format_id');
            $table->integer('version');
            $table->string('format_string');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('updated_by');
            $table->foreign('number_format_id')->references('id')->on('number_formats')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('number_format_versions');
    }
};
