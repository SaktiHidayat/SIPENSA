<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tingkat_keterlibatans')) {
            return;
        }

        $rules = [
            'Hanya diri sendiri' => ['kategori' => 'Sangat Rendah', 'skala_fuzzy' => 0],
            'Melibatkan siswa' => ['kategori' => 'Rendah', 'skala_fuzzy' => 0.25],
            'Melibatkan guru' => ['kategori' => 'Sedang', 'skala_fuzzy' => 0.5],
            'Melibatkan siswa dan guru' => ['kategori' => 'Tinggi', 'skala_fuzzy' => 0.75],
            'Melibatkan guru dan pihak sekolah (BK, wali, staff)' => ['kategori' => 'Sangat Tinggi', 'skala_fuzzy' => 1],
        ];

        foreach ($rules as $label => $rule) {
            DB::table('tingkat_keterlibatans')
                ->where('nama_keterangan', $label)
                ->update([
                    'kategori' => $rule['kategori'],
                    'skala_fuzzy' => $rule['skala_fuzzy'],
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        //
    }
};
