<?php

namespace App\Services\Spk;

use App\Models\Alternatif;
use App\Models\JenisHukuman;
use App\Models\JenisPelanggaran;
use App\Models\Siswa;
use App\Models\Spk;
use App\Models\SpkKejadian;
use App\Models\TingkatKeterlibatan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SpkCalculationService
{
    public function process(array $filters = []): ?Spk
    {
        return DB::transaction(function () use ($filters): ?Spk {
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
            $spk = Spk::query()
                ->where('siswa_id', $siswa->id)
                ->orWhereHas('siswa', function ($query) use ($siswa) {
                    $query->where('nama', $siswa->nama)
                        ->orWhere('nisn', $siswa->nisn);
                })
                ->latest('id')
                ->first();
            $nilaiAkhirSebelumnya = (float) ($spk?->total_nilai_akhir ?? 0);
            $poinSekarang = (int) $jenisPelanggaran->poin_pelanggaran;
            $totalPoin = (int) (($spk?->total_poin ?? 0) + $poinSekarang);

            $alternatifs = Alternatif::query()
                ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan'])
                ->orderBy('id')
                ->get();
            $riwayatAlternatif = collect($spk?->alternatif_snapshot_history ?? []);
            if ($spk?->alternatif_snapshot) {
                $riwayatAlternatif->push($spk->alternatif_snapshot);
            }
            $alternatifsTerpilih = $this->selectAlternatifs($alternatifs, $siswa, $riwayatAlternatif);
            $weights = [0.75, 0.25];

            $skalaC1 = (float) $jenisPelanggaran->skala_fuzzy;
            $skalaC2 = (float) $tingkatKeterlibatan->skala_fuzzy;
            $rows = $alternatifsTerpilih->map(fn ($alternatif) => [
                'alternatif_id' => $alternatif->id,
                'nama' => $alternatif->siswa?->nama ?? '-',
                'nisn' => $alternatif->siswa?->nisn ?? '-',
                'c1' => $alternatif->jenisPelanggaran?->nama_pelanggaran ?? '-',
                'c2' => $alternatif->tingkatKeterlibatan?->nama_keterangan ?? '-',
                'poin_c1' => (int) ($alternatif->jenisPelanggaran?->poin_pelanggaran ?? 0),
                'poin_c2' => (float) $alternatif->skala_fuzzy_tingkat_keterlibatan,
                'skala_c1' => (float) $alternatif->skala_fuzzy_jenis_pelanggaran,
                'skala_c2' => (float) $alternatif->skala_fuzzy_tingkat_keterlibatan,
            ])->values();
            $rows->push([
                'nama' => $siswa->nama,
                'nisn' => $siswa->nisn,
                'c1' => $jenisPelanggaran->nama_pelanggaran,
                'c2' => $tingkatKeterlibatan->nama_keterangan,
                'poin_c1' => (int) $jenisPelanggaran->poin_pelanggaran,
                'poin_c2' => $skalaC2,
                'skala_c1' => $skalaC1,
                'skala_c2' => $skalaC2,
                'current' => true,
            ]);

            $maxC1 = max(0.0001, (float) $rows->max('skala_c1'));
            $maxC2 = max(0.0001, (float) $rows->max('skala_c2'));
            $currentNormalC1 = $this->benefitNormalize($skalaC1, $maxC1);
            $currentNormalC2 = $this->benefitNormalize($skalaC2, $maxC2);
            $nilaiAkhir = ($weights[0] * $currentNormalC1) + ($weights[1] * $currentNormalC2);
            $totalNilaiAkhir = round($nilaiAkhirSebelumnya + $nilaiAkhir, 4);
            $jenisHukuman = $this->hukumanForFinalValue($totalNilaiAkhir);

            $alternatifSnapshot = $rows->map(function (array $row) use ($weights, $maxC1, $maxC2) {
                $normalC1 = $this->benefitNormalize($row['skala_c1'], $maxC1);
                $normalC2 = $this->benefitNormalize($row['skala_c2'], $maxC2);
                $nilaiAkhirAlternatif = ($weights[0] * $normalC1) + ($weights[1] * $normalC2);
                $hukumanAlternatif = $this->hukumanForFinalValue($nilaiAkhirAlternatif);

                return array_merge($row, [
                    'normalisasi_c1' => $normalC1,
                    'normalisasi_c2' => $normalC2,
                    'bobot_c1' => $weights[0],
                    'bobot_c2' => $weights[1],
                    'kontribusi_c1' => round($weights[0] * $normalC1, 4),
                    'kontribusi_c2' => round($weights[1] * $normalC2, 4),
                    'nilai_akhir' => $nilaiAkhirAlternatif,
                    'hukuman' => $hukumanAlternatif?->nama_hukuman,
                    'rentang_poin_hukuman' => $hukumanAlternatif?->rentang_poin,
                ]);
            })->sortByDesc('nilai_akhir')->values()->all();

            $spk ??= new Spk;
            $spk->fill([
                'tanggal_spk' => now()->toDateString(),
                'siswa_id' => $siswa->id,
                'jenis_pelanggaran_id' => $jenisPelanggaran->id,
                'tingkat_keterlibatan_id' => $tingkatKeterlibatan->id,
                'poin_jenis_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
                'poin_sebelumnya' => 0,
                'poin_sekarang' => $poinSekarang,
                'total_poin' => $totalPoin,
                'skala_jenis_pelanggaran' => $skalaC1,
                'skala_tingkat_keterlibatan' => $skalaC2,
                'normalisasi_c1' => $currentNormalC1,
                'normalisasi_c2' => $currentNormalC2,
                'nilai_akhir' => $nilaiAkhir,
                'nilai_akhir_sebelumnya' => $nilaiAkhirSebelumnya,
                'nilai_akhir_sekarang' => $nilaiAkhir,
                'total_nilai_akhir' => $totalNilaiAkhir,
                'jenis_hukuman_id' => $jenisHukuman?->id,
                'kode_hukuman' => $jenisHukuman?->kode_hukuman,
                'nama_hukuman' => $jenisHukuman?->nama_hukuman,
                'rentang_poin_hukuman' => $jenisHukuman?->rentang_poin,
                'alternatif_snapshot' => $alternatifSnapshot,
                'alternatif_snapshot_history' => $riwayatAlternatif->push($alternatifSnapshot)->values()->all(),
            ]);
            $spk->kode_spk ??= 'SPK-'.now()->format('YmdHisv').'-'.strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
            $spk->save();

            $kejadian = SpkKejadian::create([
                'siswa_id' => $siswa->id,
                'jenis_pelanggaran_id' => $jenisPelanggaran->id,
                'tingkat_keterlibatan_id' => $tingkatKeterlibatan->id,
                'poin_snapshot' => $poinSekarang,
                'tanggal_kejadian' => now()->toDateString(),
                'spk_id' => $spk->id,
            ]);

            return $spk;
        });
    }

    public function listResults(array $filters = []): Collection
    {
        return Spk::query()
            ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan', 'jenisHukuman'])
            ->when($filters['tanggal'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', $value))
            ->when($filters['tanggal_mulai'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '>=', $value))
            ->when($filters['tanggal_akhir'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '<=', $value))
            ->when($filters['search'] ?? null, function ($query, $value) {
                $query->whereHas('siswa', function ($studentQuery) use ($value) {
                    $studentQuery->where('nama', 'like', "%{$value}%")
                        ->orWhere('nisn', 'like', "%{$value}%");
                });
            })
            ->when(
                ! empty($filters['hidden_ids'])
                    && empty($filters['search'])
                    && empty($filters['tanggal'])
                    && empty($filters['tanggal_mulai'])
                    && empty($filters['tanggal_akhir']),
                fn ($query) => $query->whereNotIn('id', $filters['hidden_ids'])
            )
            ->latest('tanggal_spk')
            ->latest('id')
            ->get();
    }

    public function exportDataset(array $filters = []): Collection
    {
        return Spk::query()
            ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan', 'jenisHukuman'])
            ->when($filters['tanggal'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', $value))
            ->when($filters['tanggal_mulai'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '>=', $value))
            ->when($filters['tanggal_akhir'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '<=', $value))
            ->when($filters['search'] ?? null, function ($query, $value) {
                $query->whereHas('siswa', function ($studentQuery) use ($value) {
                    $studentQuery->where('nama', 'like', "%{$value}%")
                        ->orWhere('nisn', 'like', "%{$value}%");
                });
            })
            ->latest('tanggal_spk')
            ->latest('id')
            ->get();
    }

    private function normalizedWeights(Collection $kriterias): array
    {
        if ($kriterias->count() < 2) {
            throw ValidationException::withMessages([
                'siswa_id' => 'Minimal dua kriteria diperlukan untuk perhitungan SAW.',
            ]);
        }

        $total = (float) $kriterias->sum('bobot');

        if ($total <= 0) {
            throw ValidationException::withMessages([
                'siswa_id' => 'Total bobot kriteria harus lebih besar dari nol.',
            ]);
        }

        return $kriterias->take(2)->map(fn ($kriteria) => (float) $kriteria->bobot / $total)->values()->all();
    }

    private function selectAlternatifs(Collection $alternatifs, Siswa $excludedSiswa, ?Collection $history = null): Collection
    {
        $alternatifs = $alternatifs
            ->filter(function (Alternatif $alternatif) use ($excludedSiswa): bool {
                $isSameId = (int) $alternatif->siswa_id === (int) $excludedSiswa->id;
                $isSameName = $alternatif->siswa?->nama === $excludedSiswa->nama;
                $isSameNisn = $excludedSiswa->nisn && $alternatif->siswa?->nisn === $excludedSiswa->nisn;

                return ! ($isSameId || $isSameName || $isSameNisn);
            })
            ->values();

        if ($alternatifs->count() < 4) {
            throw ValidationException::withMessages([
                'siswa_id' => 'Minimal empat alternatif lain diperlukan agar total alternatif dalam proses menjadi lima.',
            ]);
        }

        $usedCombinations = Spk::query()
            ->get(['alternatif_snapshot'])
            ->map(function (Spk $spk): string {
                $snapshot = $spk->alternatif_snapshot;
                $ids = collect($snapshot ?? [])
                    ->pluck('alternatif_id')
                    ->filter()
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values();

                return $ids->count() === 4 ? $ids->implode(',') : '';
            })
            ->filter()
            ->all();

        foreach ($history ?? collect() as $snapshot) {
            $ids = collect($snapshot)
                ->pluck('alternatif_id')
                ->filter()
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values();

            if ($ids->count() === 4) {
                $usedCombinations[] = $ids->implode(',');
            }
        }

        for ($attempt = 0; $attempt < 100; $attempt++) {
            $selection = $alternatifs->shuffle()->take(4)->values();
            $signature = $selection->pluck('id')->sort()->values()->implode(',');

            if (! in_array($signature, $usedCombinations, true)) {
                return $selection;
            }
        }

        return $alternatifs->shuffle()->take(4)->values();
    }

    private function benefitNormalize(float $value, float $max): float
    {
        return $max > 0 ? round($value / $max, 4) : 0;
    }

    private function hukumanForFinalValue(float $finalValue): ?JenisHukuman
    {
        $hukumans = JenisHukuman::query()->oldest('id')->get();

        return $hukumans->first(fn (JenisHukuman $hukuman) => $hukuman->containsFinalValue($finalValue));
    }
}
