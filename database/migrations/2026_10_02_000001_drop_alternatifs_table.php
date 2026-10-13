<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('alternatifs');
    }

    public function down(): void
    {
        Schema::create('alternatifs', function (Blueprint $table): void {
            $table->id();
            $table->string('kode_alternatif')->unique();
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->cascadeOnDelete();
            $table->string('nisn', 10)->nullable();
            $table->foreignId('jenis_pelanggaran_id')->nullable()->constrained('jenis_pelanggarans')->restrictOnDelete();
            $table->unsignedInteger('poin_jenis_pelanggaran')->nullable();
            $table->decimal('skala_fuzzy_jenis_pelanggaran', 4, 2)->nullable();
            $table->foreignId('tingkat_keterlibatan_id')->nullable()->constrained('tingkat_keterlibatans')->restrictOnDelete();
            $table->decimal('skala_fuzzy_tingkat_keterlibatan', 4, 2)->nullable();
            $table->timestamps();
        });
    }
};