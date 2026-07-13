<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_spk', 50)->unique();
            $table->date('tanggal_spk');
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggarans')->cascadeOnDelete();
            $table->foreignId('tingkat_keterlibatan_id')->constrained('tingkat_keterlibatans')->cascadeOnDelete();
            $table->unsignedInteger('poin_jenis_pelanggaran')->nullable();
            $table->decimal('skala_jenis_pelanggaran', 8, 4)->nullable();
            $table->decimal('skala_tingkat_keterlibatan', 8, 4)->nullable();
            $table->decimal('normalisasi_c1', 8, 4)->nullable();
            $table->decimal('normalisasi_c2', 8, 4)->nullable();
            $table->decimal('nilai_akhir', 8, 4)->nullable();
            $table->foreignId('jenis_hukuman_id')->nullable()->constrained('jenis_hukumans')->nullOnDelete();
            $table->string('kode_hukuman', 50)->nullable();
            $table->string('nama_hukuman', 255)->nullable();
            $table->string('kategori_hukuman', 30)->nullable();
            $table->string('rentang_poin_hukuman', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spks');
    }
};
