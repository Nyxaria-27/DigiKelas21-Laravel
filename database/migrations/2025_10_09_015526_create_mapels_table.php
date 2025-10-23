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
        // migration: create_mapel_table.php
Schema::create('mapel', function (Blueprint $table) {
    $table->id();
    $table->string('nama')->unique();
    $table->string('kode')->nullable()->unique(); // optional
    $table->text('deskripsi')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel');
    }
};
