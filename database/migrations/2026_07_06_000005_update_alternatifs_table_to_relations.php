<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('alternatifs')) {
            return;
        }

        if (Schema::hasColumn('alternatifs', 'nama_alternatif')) {
            foreach (['nama_alternatif', 'kategori', 'rentang_poin', 'keterangan'] as $column) {
                if (Schema::hasColumn('alternatifs', $column)) {
                    DB::statement("ALTER TABLE alternatifs DROP COLUMN {$column}");
                }
            }
        }

        if (!Schema::hasColumn('alternatifs', 'siswa_id')) {
            DB::statement('ALTER TABLE alternatifs ADD COLUMN siswa_id BIGINT UNSIGNED NULL AFTER kode_alternatif');
        }

        if (!Schema::hasColumn('alternatifs', 'nisn')) {
            DB::statement('ALTER TABLE alternatifs ADD COLUMN nisn VARCHAR(10) NULL AFTER siswa_id');
        }

        if (!Schema::hasColumn('alternatifs', 'jenis_pelanggaran_id')) {
            DB::statement('ALTER TABLE alternatifs ADD COLUMN jenis_pelanggaran_id BIGINT UNSIGNED NULL AFTER nisn');
        }

        if (!Schema::hasColumn('alternatifs', 'poin_jenis_pelanggaran')) {
            DB::statement('ALTER TABLE alternatifs ADD COLUMN poin_jenis_pelanggaran INT UNSIGNED NULL AFTER jenis_pelanggaran_id');
        }

        if (!Schema::hasColumn('alternatifs', 'skala_fuzzy_jenis_pelanggaran')) {
            DB::statement('ALTER TABLE alternatifs ADD COLUMN skala_fuzzy_jenis_pelanggaran DECIMAL(4,2) NULL AFTER poin_jenis_pelanggaran');
        }

        if (!Schema::hasColumn('alternatifs', 'tingkat_keterlibatan_id')) {
            DB::statement('ALTER TABLE alternatifs ADD COLUMN tingkat_keterlibatan_id BIGINT UNSIGNED NULL AFTER poin_jenis_pelanggaran');
        }

        if (!Schema::hasColumn('alternatifs', 'skala_fuzzy_tingkat_keterlibatan')) {
            DB::statement('ALTER TABLE alternatifs ADD COLUMN skala_fuzzy_tingkat_keterlibatan DECIMAL(4,2) NULL AFTER tingkat_keterlibatan_id');
        }

        if (Schema::hasColumn('alternatifs', 'siswa_id')) {
            DB::statement('ALTER TABLE alternatifs ADD CONSTRAINT alternatifs_siswa_id_foreign FOREIGN KEY (siswa_id) REFERENCES siswas(id) ON DELETE CASCADE');
        }

        if (Schema::hasColumn('alternatifs', 'jenis_pelanggaran_id')) {
            DB::statement('ALTER TABLE alternatifs ADD CONSTRAINT alternatifs_jenis_pelanggaran_id_foreign FOREIGN KEY (jenis_pelanggaran_id) REFERENCES jenis_pelanggarans(id) ON DELETE RESTRICT');
        }

        if (Schema::hasColumn('alternatifs', 'tingkat_keterlibatan_id')) {
            DB::statement('ALTER TABLE alternatifs ADD CONSTRAINT alternatifs_tingkat_keterlibatan_id_foreign FOREIGN KEY (tingkat_keterlibatan_id) REFERENCES tingkat_keterlibatans(id) ON DELETE RESTRICT');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('alternatifs')) {
            return;
        }

        if (Schema::hasColumn('alternatifs', 'siswa_id')) {
            foreach ([
                'alternatifs_siswa_id_foreign',
                'alternatifs_jenis_pelanggaran_id_foreign',
                'alternatifs_tingkat_keterlibatan_id_foreign',
            ] as $constraint) {
                try {
                    DB::statement("ALTER TABLE alternatifs DROP FOREIGN KEY {$constraint}");
                } catch (\Throwable) {
                    //
                }
            }

            foreach ([
                'skala_fuzzy_tingkat_keterlibatan',
                'tingkat_keterlibatan_id',
                'skala_fuzzy_jenis_pelanggaran',
                'poin_jenis_pelanggaran',
                'jenis_pelanggaran_id',
                'nisn',
                'siswa_id',
            ] as $column) {
                if (Schema::hasColumn('alternatifs', $column)) {
                    DB::statement("ALTER TABLE alternatifs DROP COLUMN {$column}");
                }
            }

            if (!Schema::hasColumn('alternatifs', 'nama_alternatif')) {
                DB::statement('ALTER TABLE alternatifs ADD COLUMN nama_alternatif VARCHAR(255) NULL AFTER kode_alternatif');
            }

            if (!Schema::hasColumn('alternatifs', 'kategori')) {
                DB::statement('ALTER TABLE alternatifs ADD COLUMN kategori ENUM(\'ringan\', \'sedang\', \'berat\') NULL AFTER nama_alternatif');
            }

            if (!Schema::hasColumn('alternatifs', 'rentang_poin')) {
                DB::statement('ALTER TABLE alternatifs ADD COLUMN rentang_poin VARCHAR(30) NULL AFTER kategori');
            }

            if (!Schema::hasColumn('alternatifs', 'keterangan')) {
                DB::statement('ALTER TABLE alternatifs ADD COLUMN keterangan TEXT NULL AFTER rentang_poin');
            }
        }
    }
};
