<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_hukumans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_hukuman', 50)->unique();
            $table->enum('kategori', ['rendah', 'sedang', 'tinggi']);
            $table->string('rentang_poin', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_hukumans');
    }
};
