<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\JenisPelanggaran;
use App\Models\Siswa;
use App\Models\TingkatKeterlibatan;
use Illuminate\Database\Seeder;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        $siswas = Siswa::query()->oldest()->get();
        $jenisPelanggarans = JenisPelanggaran::query()->oldest()->get();
        $tingkatKeterlibatans = TingkatKeterlibatan::query()->oldest()->get();

        if ($siswas->isEmpty() || $jenisPelanggarans->isEmpty() || $tingkatKeterlibatans->isEmpty()) {
            return;
        }

        Alternatif::query()->delete();

        for ($i = 0; $i < 10; $i++) {
            $siswa = $siswas->random();
            $jenisPelanggaran = $jenisPelanggarans->random();
            $tingkatKeterlibatan = $tingkatKeterlibatans->random();

            Alternatif::create([
                'kode_alternatif' => 'ALT'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                'siswa_id' => $siswa->id,
                'nisn' => $siswa->nisn,
                'jenis_pelanggaran_id' => $jenisPelanggaran->id,
                'poin_jenis_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
                'skala_fuzzy_jenis_pelanggaran' => $jenisPelanggaran->skala_fuzzy,
                'tingkat_keterlibatan_id' => $tingkatKeterlibatan->id,
                'skala_fuzzy_tingkat_keterlibatan' => $tingkatKeterlibatan->skala_fuzzy,
            ]);
        }
    }
}
