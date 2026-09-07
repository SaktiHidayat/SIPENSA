<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_pelanggarans', function (Blueprint $table) {
            if (! Schema::hasColumn('jenis_pelanggarans', 'rentang_poin')) {
                $table->string('rentang_poin', 20)->after('poin_pelanggaran');
            }
        });

        Schema::table('tingkat_keterlibatans', function (Blueprint $table) {
            if (Schema::hasColumn('tingkat_keterlibatans', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tingkat_keterlibatans', function (Blueprint $table) {
            if (! Schema::hasColumn('tingkat_keterlibatans', 'kategori')) {
                $table->string('kategori')->after('poin_keterlibatan');
            }
        });

        Schema::table('jenis_pelanggarans', function (Blueprint $table) {
            if (Schema::hasColumn('jenis_pelanggarans', 'rentang_poin')) {
                $table->dropColumn('rentang_poin');
            }
        });
    }
};