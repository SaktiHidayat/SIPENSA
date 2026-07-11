<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tingkat_keterlibatans') && Schema::hasColumn('tingkat_keterlibatans', 'poin_keterlibatan')) {
            DB::statement('ALTER TABLE tingkat_keterlibatans DROP COLUMN poin_keterlibatan');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tingkat_keterlibatans') && !Schema::hasColumn('tingkat_keterlibatans', 'poin_keterlibatan')) {
            DB::statement('ALTER TABLE tingkat_keterlibatans ADD COLUMN poin_keterlibatan INT UNSIGNED NOT NULL AFTER nama_keterangan');
        }
    }
};
