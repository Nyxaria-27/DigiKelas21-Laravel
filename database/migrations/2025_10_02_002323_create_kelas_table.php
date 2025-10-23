<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->string('kode_siswa')->nullable()->unique();
            $table->string('kode_guru')->nullable()->unique();
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('cascade');

            // pastikan kode_kelas lama tetap ada or deprecated
            // $table->string('kode_kelas')->nullable()->unique()->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
