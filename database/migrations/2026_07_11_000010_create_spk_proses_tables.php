<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_proses', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_proses');
            $table->string('kode_proses', 50)->unique();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('spk_proses_hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_proses_id')->constrained('spk_proses')->cascadeOnDelete();
            $table->foreignId('alternatif_id')->constrained('alternatifs')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->string('kode_alternatif', 50);
            $table->string('nisn', 10);
            $table->unsignedInteger('poin_c1')->nullable();
            $table->decimal('skala_c1', 4, 2)->nullable();
            $table->decimal('normalisasi_c1', 8, 4)->nullable();
            $table->decimal('skala_c2', 4, 2)->nullable();
            $table->decimal('normalisasi_c2', 8, 4)->nullable();
            $table->decimal('nilai_akhir', 8, 4)->nullable();
            $table->unsignedInteger('ranking')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_proses_hasils');
        Schema::dropIfExists('spk_proses');
    }
};
