<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spks', function (Blueprint $table) {
            if (! Schema::hasColumn('spks', 'alternatif_snapshot_history')) {
                $table->json('alternatif_snapshot_history')->nullable()->after('alternatif_snapshot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spks', function (Blueprint $table) {
            if (Schema::hasColumn('spks', 'alternatif_snapshot_history')) {
                $table->dropColumn('alternatif_snapshot_history');
            }
        });
    }
};