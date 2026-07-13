<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('spks')) {
            return;
        }

        Schema::table('spks', function (Blueprint $table) {
            if (Schema::hasColumn('spks', 'frekuensi')) {
                $table->dropColumn('frekuensi');
            }

            if (Schema::hasColumn('spks', 'skala_frekuensi')) {
                $table->dropColumn('skala_frekuensi');
            }

            if (Schema::hasColumn('spks', 'normalisasi_c3')) {
                $table->dropColumn('normalisasi_c3');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('spks')) {
            return;
        }

        Schema::table('spks', function (Blueprint $table) {
            if (! Schema::hasColumn('spks', 'frekuensi')) {
                $table->unsignedInteger('frekuensi')->default(1)->after('jenis_pelanggaran_id');
            }

            if (! Schema::hasColumn('spks', 'skala_frekuensi')) {
                $table->decimal('skala_frekuensi', 8, 4)->nullable()->after('skala_jenis_pelanggaran');
            }

            if (! Schema::hasColumn('spks', 'normalisasi_c3')) {
                $table->decimal('normalisasi_c3', 8, 4)->nullable()->after('normalisasi_c2');
            }
        });
    }
};
