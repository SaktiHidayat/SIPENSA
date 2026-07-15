<?php

namespace App\Services\Spk;

use App\Models\Alternatif;
use App\Models\JenisHukuman;
use App\Models\JenisPelanggaran;
use App\Models\Kriteria;
use App\Models\Spk;
use App\Models\Siswa;
use App\Models\TingkatKeterlibatan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SpkCalculationService
{
    public function process(array $filters = []): Spk
    {
        return DB::transaction(function () use ($filters): Spk {
            // 1. Menentukan data masukan dari form proses SPK.
            $siswaId = $filters['siswa_id'] ?? null;
            $jenisPelanggaranId = $filters['jenis_pelanggaran_id'] ?? null;
            $tingkatKeterlibatanId = $filters['tingkat_keterlibatan_id'] ?? null;

            if (! $siswaId || ! $jenisPelanggaranId || ! $tingkatKeterlibatanId) {
                throw ValidationException::withMessages([
                    'siswa_id' => 'Siswa, jenis pelanggaran, dan tingkat keterlibatan wajib dipilih.',
                ]);
            }

            $siswa = Siswa::query()->findOrFail($siswaId);
            $jenisPelanggaran = JenisPelanggaran::query()->findOrFail($jenisPelanggaranId);
            $tingkatKeterlibatan = TingkatKeterlibatan::query()->findOrFail($tingkatKeterlibatanId);

            // 2. Mengambil nilai fuzzy dari master data yang dipilih.
            $comparisonQuery = Alternatif::query()
                ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan']);

            if ((clone $comparisonQuery)->count() === 0) {
                throw ValidationException::withMessages([
                    'siswa_id' => 'Data alternatif belum tersedia!',
                ]);
            }

            $comparisonCount = (clone $comparisonQuery)->count();

            if ($comparisonCount < 2) {
                throw ValidationException::withMessages([
                    'siswa_id' => 'Data alternatif untuk proses penilaian minimal 2.',
                ]);
            }

            $comparisonLimit = random_int(2, min(10, $comparisonCount));
            $comparisonAlternatifs = $comparisonQuery->inRandomOrder()->limit($comparisonLimit)->get();

            // 3. Menentukan bobot kriteria dari tabel kriterias dan menormalkannya.
            $kriterias = Kriteria::query()->oldest()->take(2)->get();
            $weights = $this->normalizedWeights($kriterias);

            // 4. Menentukan data pembanding dari tabel alternatifs untuk normalisasi.
            $maxC1 = max(0.0001, (float) $comparisonAlternatifs->max('skala_fuzzy_jenis_pelanggaran'));
            $maxC2 = max(0.0001, (float) $comparisonAlternatifs->max('skala_fuzzy_tingkat_keterlibatan'));

            $skalaC1 = (float) $jenisPelanggaran->skala_fuzzy;
            $skalaC2 = (float) $tingkatKeterlibatan->skala_fuzzy;

            // 5. Tahap FMADM: data kualitatif sudah menjadi nilai fuzzy siap olah.
            // 6. Tahap SAW normalisasi: nilai fuzzy dibagi nilai maksimum pembanding.
            $normalC1 = $this->benefitNormalize($skalaC1, $maxC1);
            $normalC2 = $this->benefitNormalize($skalaC2, $maxC2);

            // 7. Perhitungan nilai akhir:
            //    nilai akhir = (bobot C1 x normalisasi C1) + (bobot C2 x normalisasi C2).
            $nilaiAkhir = (($weights[0] ?? 0.3333) * $normalC1)
                + (($weights[1] ?? 0.3333) * $normalC2);

            // 8. Menentukan jenis hukuman berdasarkan nilai akhir.
            $hukumanRule = JenisHukuman::ruleForScore($nilaiAkhir);
            $jenisHukumans = JenisHukuman::query()
                ->where('kategori', strtolower($hukumanRule['kategori']))
                ->orderBy('id')
                ->get();

            $kodeHukuman = $jenisHukumans->pluck('kode_hukuman')->filter()->implode(', ');
            $namaHukuman = $jenisHukumans->pluck('nama_hukuman')->filter()->implode(', ');
            $jenisHukuman = $jenisHukumans->first();
            $alternatifSnapshot = $comparisonAlternatifs->map(function ($alternatif) use ($weights, $maxC1, $maxC2) {
                $altSkalaC1 = (float) $alternatif->skala_fuzzy_jenis_pelanggaran;
                $altSkalaC2 = (float) $alternatif->skala_fuzzy_tingkat_keterlibatan;
                $altNormalC1 = $this->benefitNormalize($altSkalaC1, $maxC1);
                $altNormalC2 = $this->benefitNormalize($altSkalaC2, $maxC2);
                $altNilaiAkhir = (($weights[0] ?? 0.5) * $altNormalC1)
                    + (($weights[1] ?? 0.5) * $altNormalC2);
                $altHukumanRule = JenisHukuman::ruleForScore($altNilaiAkhir);
                $altJenisHukumans = JenisHukuman::query()
                    ->where('kategori', strtolower($altHukumanRule['kategori']))
                    ->orderBy('id')
                    ->get();

                return [
                    'nama' => $alternatif->siswa?->nama ?? '-',
                    'nisn' => $alternatif->siswa?->nisn ?? '-',
                    'c1' => $alternatif->jenisPelanggaran?->nama_pelanggaran ?? '-',
                    'skala_c1' => $altSkalaC1,
                    'c2' => $alternatif->tingkatKeterlibatan?->nama_keterangan ?? '-',
                    'skala_c2' => $altSkalaC2,
                    'normalisasi_c1' => $altNormalC1,
                    'normalisasi_c2' => $altNormalC2,
                    'nilai_akhir' => $altNilaiAkhir,
                    'hukuman' => $altJenisHukumans->pluck('nama_hukuman')->filter()->implode(', '),
                    'kategori_hukuman' => $altHukumanRule['kategori'],
                ];
            })->values()->all();

            // 9. Menyimpan hasil ke tabel spks sebagai riwayat SPK.
            return Spk::create([
                'kode_spk' => 'SPK-'.now()->format('YmdHisv').'-'.strtoupper(substr(bin2hex(random_bytes(2)), 0, 4)),
                'tanggal_spk' => now()->toDateString(),
                'siswa_id' => $siswa->id,
                'jenis_pelanggaran_id' => $jenisPelanggaran->id,
                'tingkat_keterlibatan_id' => $tingkatKeterlibatan->id,
                'poin_jenis_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
                'skala_jenis_pelanggaran' => $skalaC1,
                'skala_tingkat_keterlibatan' => $skalaC2,
                'normalisasi_c1' => $normalC1,
                'normalisasi_c2' => $normalC2,
                'nilai_akhir' => $nilaiAkhir,
                'jenis_hukuman_id' => $jenisHukuman?->id,
                'kode_hukuman' => $kodeHukuman ?: $jenisHukuman?->kode_hukuman,
                'nama_hukuman' => $namaHukuman ?: $jenisHukuman?->nama_hukuman,
                'kategori_hukuman' => $hukumanRule['kategori'],
                'rentang_poin_hukuman' => $hukumanRule['rentang_poin'],
                'alternatif_snapshot' => $alternatifSnapshot,
            ]);
        });
    }

    public function listResults(array $filters = []): Collection
    {
        // 10. Menampilkan hasil di web dan PDF berdasarkan filter tanggal.
        return Spk::query()
            ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan', 'jenisHukuman'])
            ->when($filters['tanggal'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', $value))
            ->latest('tanggal_spk')
            ->latest('id')
            ->get();
    }

    public function exportDataset(array $filters = []): Collection
    {
        return Spk::query()
            ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan', 'jenisHukuman'])
            ->when($filters['tanggal'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', $value))
            ->latest('tanggal_spk')
            ->latest('id')
            ->get();
    }

    private function normalizedWeights(Collection $kriterias): array
    {
        $total = max(1, (float) $kriterias->sum('bobot'));

        return $kriterias->take(3)->map(fn ($kriteria) => (float) $kriteria->bobot / $total)->values()->all();
    }

    private function benefitNormalize(float $value, float $max): float
    {
        return $max > 0 ? round($value / $max, 4) : 0;
    }
}
