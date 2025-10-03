<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengumpulan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade')->unique();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade')->unique();
            $table->string('path_file_jawaban')->nullable();
            $table->text('teks_jawaban')->nullable();
            $table->integer('nilai')->nullable();
            $table->text('komentar')->nullable();
            $table->timestamp('tanggal_kumpul')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan');
    }
};
