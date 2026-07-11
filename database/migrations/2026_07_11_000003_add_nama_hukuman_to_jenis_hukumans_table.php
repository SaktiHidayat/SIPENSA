<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jenis_hukumans')) {
            return;
        }

        Schema::table('jenis_hukumans', function (Blueprint $table) {
            if (! Schema::hasColumn('jenis_hukumans', 'kode_hukuman')) {
                $table->string('kode_hukuman', 50)->unique()->after('id');
            }

            if (! Schema::hasColumn('jenis_hukumans', 'nama_hukuman')) {
                if (Schema::hasColumn('jenis_hukumans', 'kode_hukuman')) {
                    $table->string('nama_hukuman', 255)->after('kode_hukuman');
                } else {
                    $table->string('nama_hukuman', 255)->after('id');
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('jenis_hukumans')) {
            return;
        }

        Schema::table('jenis_hukumans', function (Blueprint $table) {
            if (Schema::hasColumn('jenis_hukumans', 'nama_hukuman')) {
                $table->dropColumn('nama_hukuman');
            }

            if (Schema::hasColumn('jenis_hukumans', 'kode_hukuman')) {
                $table->dropColumn('kode_hukuman');
            }
        });
    }
};
