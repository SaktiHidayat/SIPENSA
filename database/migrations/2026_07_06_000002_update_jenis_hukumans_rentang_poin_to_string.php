<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jenis_hukumans')) {
            DB::statement('ALTER TABLE jenis_hukumans MODIFY rentang_poin VARCHAR(30) NOT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('jenis_hukumans')) {
            DB::statement('ALTER TABLE jenis_hukumans MODIFY rentang_poin DECIMAL(4,2) NOT NULL');
        }
    }
};
