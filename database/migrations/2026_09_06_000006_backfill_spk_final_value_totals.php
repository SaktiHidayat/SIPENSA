<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('spks') || ! Schema::hasColumn('spks', 'total_nilai_akhir')) {
            return;
        }

        DB::table('spks')->update([
            'nilai_akhir_sekarang' => DB::raw('COALESCE(nilai_akhir, 0)'),
            'total_nilai_akhir' => DB::raw('COALESCE(nilai_akhir, 0)'),
        ]);
    }

    public function down(): void
    {
        // Existing nilai_akhir data remains intact when this backfill is rolled back.
    }
};