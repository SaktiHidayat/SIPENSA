<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spks', function (Blueprint $table) {
            if (! Schema::hasColumn('spks', 'nilai_akhir_sebelumnya')) {
                $table->decimal('nilai_akhir_sebelumnya', 8, 4)->default(0)->after('nilai_akhir');
            }

            if (! Schema::hasColumn('spks', 'nilai_akhir_sekarang')) {
                $table->decimal('nilai_akhir_sekarang', 8, 4)->default(0)->after('nilai_akhir_sebelumnya');
            }

            if (! Schema::hasColumn('spks', 'total_nilai_akhir')) {
                $table->decimal('total_nilai_akhir', 8, 4)->default(0)->after('nilai_akhir_sekarang');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spks', function (Blueprint $table) {
            foreach (['total_nilai_akhir', 'nilai_akhir_sekarang', 'nilai_akhir_sebelumnya'] as $column) {
                if (Schema::hasColumn('spks', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};