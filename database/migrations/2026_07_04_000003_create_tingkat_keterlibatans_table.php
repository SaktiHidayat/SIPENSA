<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tingkat_keterlibatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tingkat_keterlibatan', 50)->unique();
            $table->string('nama_keterangan');
            $table->unsignedSmallInteger('poin_keterlibatan');
            $table->string('kategori');
            $table->decimal('skala_fuzzy', 4, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tingkat_keterlibatans');
    }
};
