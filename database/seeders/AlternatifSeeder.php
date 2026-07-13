<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\JenisPelanggaran;
use App\Models\Siswa;
use App\Models\TingkatKeterlibatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

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

        $pairs = $this->buildPairs($jenisPelanggarans, $tingkatKeterlibatans);

        for ($i = 0; $i < 15; $i++) {
            $pair = $pairs[$i % $pairs->count()];
            $siswa = $siswas[$i % $siswas->count()];

            Alternatif::create([
                'kode_alternatif' => 'ALT'.str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                'siswa_id' => $siswa->id,
                'nisn' => $siswa->nisn,
                'jenis_pelanggaran_id' => $pair['jenis']->id,
                'poin_jenis_pelanggaran' => $pair['jenis']->poin_pelanggaran,
                'skala_fuzzy_jenis_pelanggaran' => $pair['jenis']->skala_fuzzy,
                'tingkat_keterlibatan_id' => $pair['keterlibatan']->id,
                'skala_fuzzy_tingkat_keterlibatan' => $pair['keterlibatan']->skala_fuzzy,
            ]);
        }
    }

    private function buildPairs(Collection $jenisPelanggarans, Collection $tingkatKeterlibatans): Collection
    {
        $pairs = collect();

        foreach ($jenisPelanggarans as $index => $jenis) {
            $keterlibatan = $tingkatKeterlibatans[$index % $tingkatKeterlibatans->count()];

            $pairs->push([
                'jenis' => $jenis,
                'keterlibatan' => $keterlibatan,
            ]);
        }

        foreach ($tingkatKeterlibatans as $index => $keterlibatan) {
            $jenis = $jenisPelanggarans[$index % $jenisPelanggarans->count()];

            $pairs->push([
                'jenis' => $jenis,
                'keterlibatan' => $keterlibatan,
            ]);
        }

        return $pairs->shuffle()->values();
    }
}
