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
            if (! Schema::hasColumn('spks', 'alternatif_snapshot')) {
                $table->json('alternatif_snapshot')->nullable()->after('rentang_poin_hukuman');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('spks')) {
            return;
        }

        Schema::table('spks', function (Blueprint $table) {
            if (Schema::hasColumn('spks', 'alternatif_snapshot')) {
                $table->dropColumn('alternatif_snapshot');
            }
        });
    }
};
