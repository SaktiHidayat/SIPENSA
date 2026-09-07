<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spks', function (Blueprint $table) {
            if (! Schema::hasColumn('spks', 'poin_sebelumnya')) {
                $table->unsignedInteger('poin_sebelumnya')->default(0)->after('poin_jenis_pelanggaran');
            }

            if (! Schema::hasColumn('spks', 'poin_sekarang')) {
                $table->unsignedInteger('poin_sekarang')->default(0)->after('poin_sebelumnya');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spks', function (Blueprint $table) {
            if (Schema::hasColumn('spks', 'poin_sekarang')) {
                $table->dropColumn('poin_sekarang');
            }

            if (Schema::hasColumn('spks', 'poin_sebelumnya')) {
                $table->dropColumn('poin_sebelumnya');
            }
        });
    }
};