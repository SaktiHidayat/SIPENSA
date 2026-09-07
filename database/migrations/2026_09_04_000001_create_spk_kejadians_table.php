<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_kejadians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggarans')->restrictOnDelete();
            $table->foreignId('tingkat_keterlibatan_id')->constrained('tingkat_keterlibatans')->restrictOnDelete();
            $table->unsignedInteger('poin_snapshot');
            $table->date('tanggal_kejadian');
            $table->foreignId('spk_id')->nullable()->constrained('spks')->nullOnDelete();
            $table->timestamps();

            $table->index(['siswa_id', 'tanggal_kejadian']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_kejadians');
    }
};
