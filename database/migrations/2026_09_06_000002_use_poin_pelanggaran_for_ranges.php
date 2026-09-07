<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('jenis_pelanggarans')) {
            return;
        }

        DB::table('jenis_pelanggarans')->update([
            'rentang_poin' => DB::raw("COALESCE(NULLIF(rentang_poin, ''), poin_pelanggaran)"),
        ]);

        Schema::table('jenis_pelanggarans', function (Blueprint $table) {
            $table->string('poin_pelanggaran', 20)->change();
            $table->dropColumn('rentang_poin');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_pelanggarans', function (Blueprint $table) {
            $table->unsignedSmallInteger('poin_pelanggaran')->change();
            $table->string('rentang_poin', 20)->nullable()->after('poin_pelanggaran');
        });
    }
};