<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jenis_hukumans')) {
            return;
        }

        DB::statement("ALTER TABLE jenis_hukumans MODIFY kategori ENUM('sangat rendah', 'rendah', 'sedang', 'tinggi', 'sangat tinggi') NOT NULL");
    }

    public function down(): void
    {
        if (! Schema::hasTable('jenis_hukumans')) {
            return;
        }

        DB::statement("ALTER TABLE jenis_hukumans MODIFY kategori ENUM('rendah', 'sedang', 'tinggi') NOT NULL");
    }
};
