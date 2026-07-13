<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('spk_proses_hasils')) {
            return;
        }

        Schema::table('spk_proses_hasils', function (Blueprint $table) {
            if (! Schema::hasColumn('spk_proses_hasils', 'jenis_hukuman_id')) {
                $table->foreignId('jenis_hukuman_id')->nullable()->after('nilai_akhir')->constrained('jenis_hukumans')->nullOnDelete();
            }

            if (! Schema::hasColumn('spk_proses_hasils', 'kode_hukuman')) {
                $table->string('kode_hukuman', 50)->nullable()->after('jenis_hukuman_id');
            }

            if (! Schema::hasColumn('spk_proses_hasils', 'nama_hukuman')) {
                $table->string('nama_hukuman', 255)->nullable()->after('kode_hukuman');
            }

            if (! Schema::hasColumn('spk_proses_hasils', 'kategori_hukuman')) {
                $table->string('kategori_hukuman', 30)->nullable()->after('nama_hukuman');
            }

            if (! Schema::hasColumn('spk_proses_hasils', 'rentang_poin_hukuman')) {
                $table->string('rentang_poin_hukuman', 30)->nullable()->after('kategori_hukuman');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('spk_proses_hasils')) {
            return;
        }

        Schema::table('spk_proses_hasils', function (Blueprint $table) {
            if (Schema::hasColumn('spk_proses_hasils', 'rentang_poin_hukuman')) {
                $table->dropColumn('rentang_poin_hukuman');
            }

            if (Schema::hasColumn('spk_proses_hasils', 'kategori_hukuman')) {
                $table->dropColumn('kategori_hukuman');
            }

            if (Schema::hasColumn('spk_proses_hasils', 'nama_hukuman')) {
                $table->dropColumn('nama_hukuman');
            }

            if (Schema::hasColumn('spk_proses_hasils', 'kode_hukuman')) {
                $table->dropColumn('kode_hukuman');
            }

            if (Schema::hasColumn('spk_proses_hasils', 'jenis_hukuman_id')) {
                $table->dropConstrainedForeignId('jenis_hukuman_id');
            }
        });
    }
};
