<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->change();
        });

        Schema::table('spks', function (Blueprint $table) {
            if (! Schema::hasColumn('spks', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }
        });

        Schema::create('spk_calon_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->string('kelas', 50);
            $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggarans')->restrictOnDelete();
            $table->foreignId('tingkat_keterlibatan_id')->constrained('tingkat_keterlibatans')->restrictOnDelete();
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->nullOnDelete();
            $table->timestamp('diproses_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_calon_siswas');

        Schema::table('spks', function (Blueprint $table) {
            if (Schema::hasColumn('spks', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'guru_bk'])->change();
        });
    }
};