<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jenis_hukumans') && Schema::hasColumn('jenis_hukumans', 'kategori')) {
            Schema::table('jenis_hukumans', function (Blueprint $table) {
                $table->dropColumn('kategori');
            });
        }

        if (Schema::hasTable('spks') && Schema::hasColumn('spks', 'kategori_hukuman')) {
            Schema::table('spks', function (Blueprint $table) {
                $table->dropColumn('kategori_hukuman');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('jenis_hukumans') && ! Schema::hasColumn('jenis_hukumans', 'kategori')) {
            Schema::table('jenis_hukumans', function (Blueprint $table) {
                $table->string('kategori')->nullable()->after('nama_hukuman');
            });
        }

        if (Schema::hasTable('spks') && ! Schema::hasColumn('spks', 'kategori_hukuman')) {
            Schema::table('spks', function (Blueprint $table) {
                $table->string('kategori_hukuman', 30)->nullable()->after('nama_hukuman');
            });
        }
    }
};