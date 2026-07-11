<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tingkat_keterlibatans') && Schema::hasColumn('tingkat_keterlibatans', 'rentang_poin')) {
            DB::statement('ALTER TABLE tingkat_keterlibatans DROP COLUMN rentang_poin');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tingkat_keterlibatans') && !Schema::hasColumn('tingkat_keterlibatans', 'rentang_poin')) {
            DB::statement('ALTER TABLE tingkat_keterlibatans ADD COLUMN rentang_poin VARCHAR(30) NULL AFTER skala_fuzzy');
        }
    }
};
