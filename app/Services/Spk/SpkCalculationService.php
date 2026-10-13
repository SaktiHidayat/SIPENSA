<?php

namespace App\Services\Spk;

use App\Models\JenisHukuman;
use App\Models\JenisPelanggaran;
use App\Models\Spk;
use App\Models\SpkCalonSiswa;
use App\Models\SpkKejadian;
use App\Models\TingkatKeterlibatan;
use App\Models\User;
use App\Services\FmadmSawService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SpkCalculationService
{
    public function __construct(private readonly FmadmSawService $fmadmSawService) {}

    public function processPending(Collection $candidates, User $user, string $batchId): void
    {
        DB::transaction(function () use ($candidates, $user, $batchId): void {
            $inputRows = [];
            $processedCandidates = [];

            foreach ($candidates as $candidate) {
                $siswa = $candidate->siswa;

                if (! $siswa) {
                    throw ValidationException::withMessages([
                        'siswa_id' => 'Salah satu antrean belum terhubung ke data siswa master.',
                    ]);
                }

                $jenisPelanggaran = $candidate->jenisPelanggaran;
                $tingkatKeterlibatan = $candidate->tingkatKeterlibatan;
                $inputRows[] = [
                    'alternatif_id' => $candidate->id,
                    'nama' => $siswa->nama,
                    'nisn' => $siswa->nisn,
                    'c1' => $jenisPelanggaran?->nama_pelanggaran ?? '-',
                    'c2' => $tingkatKeterlibatan?->nama_keterangan ?? '-',
                    'poin_c1' => (int) ($jenisPelanggaran?->poin_pelanggaran ?? 0),
                    'poin_c2' => (float) ($tingkatKeterlibatan?->skala_fuzzy ?? 0),
                    'skala_c2' => (float) ($tingkatKeterlibatan?->skala_fuzzy ?? 0),
                    'candidate_id' => $candidate->id,
                ];
                $processedCandidates[] = [$candidate, $siswa, $jenisPelanggaran, $tingkatKeterlibatan];
            }

            $calculatedRows = $this->fmadmSawService->calculate(
                $inputRows,
                [0.75, 0.25],
                fn (float $score): ?JenisHukuman => $this->hukumanForFinalValue($score),
            );

            foreach ($processedCandidates as [$candidate, $siswa, $jenisPelanggaran, $tingkatKeterlibatan]) {
                $currentRow = collect($calculatedRows)->first(
                    fn (array $row): bool => (int) ($row['candidate_id'] ?? 0) === (int) $candidate->id,
                );
                $currentRow ??= [];
                $poinSekarang = (int) ($jenisPelanggaran?->poin_pelanggaran ?? 0);
                $spk = Spk::query()
                    ->where('user_id', $user->id)
                    ->where('batch_id', $batchId)
                    ->where('siswa_id', $siswa->id)
                    ->latest('id')
                    ->first();
                $nilaiAkhirSebelumnya = (float) ($spk?->total_nilai_akhir ?? 0);
                $nilaiAkhir = (float) ($currentRow['nilai_akhir'] ?? 0);
                $totalNilaiAkhir = $nilaiAkhirSebelumnya + $nilaiAkhir;
                $jenisHukuman = $this->hukumanForFinalValue($totalNilaiAkhir);
                $totalPoin = (int) (($spk?->total_poin ?? 0) + $poinSekarang);

                $spk ??= new Spk;
                $spk->fill([
                    'user_id' => $user->id,
                    'batch_id' => $batchId,
                    'tanggal_spk' => now()->toDateString(),
                    'siswa_id' => $siswa->id,
                    'jenis_pelanggaran_id' => $jenisPelanggaran?->id,
                    'tingkat_keterlibatan_id' => $tingkatKeterlibatan?->id,
                    'poin_jenis_pelanggaran' => $poinSekarang,
                    'poin_sebelumnya' => (int) ($spk->total_poin ?? 0),
                    'poin_sekarang' => $poinSekarang,
                    'total_poin' => $totalPoin,
                    'skala_jenis_pelanggaran' => (float) ($currentRow['skala_c1'] ?? 0),
                    'skala_tingkat_keterlibatan' => (float) ($currentRow['skala_c2'] ?? 0),
                    'normalisasi_c1' => (float) ($currentRow['normalisasi_c1'] ?? 0),
                    'normalisasi_c2' => (float) ($currentRow['normalisasi_c2'] ?? 0),
                    'nilai_akhir' => $nilaiAkhir,
                    'nilai_akhir_sebelumnya' => $nilaiAkhirSebelumnya,
                    'nilai_akhir_sekarang' => $nilaiAkhir,
                    'total_nilai_akhir' => $totalNilaiAkhir,
                    'jenis_hukuman_id' => $jenisHukuman?->id,
                    'kode_hukuman' => $jenisHukuman?->kode_hukuman,
                    'nama_hukuman' => $jenisHukuman?->nama_hukuman,
                    'rentang_poin_hukuman' => $jenisHukuman?->rentang_poin,
                    'alternatif_snapshot' => $calculatedRows,
                    'alternatif_snapshot_history' => array_merge($spk->alternatif_snapshot_history ?? [], [$calculatedRows]),
                ]);
                $spk->kode_spk ??= 'SPK-'.now()->format('YmdHisv').'-'.strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
                $spk->save();

                SpkKejadian::create([
                    'siswa_id' => $siswa->id,
                    'jenis_pelanggaran_id' => $jenisPelanggaran?->id,
                    'tingkat_keterlibatan_id' => $tingkatKeterlibatan?->id,
                    'poin_snapshot' => $poinSekarang,
                    'tanggal_kejadian' => now()->toDateString(),
                    'spk_id' => $spk->id,
                ]);

                $candidate->update([
                    'diproses_pada' => now(),
                ]);
            }
        });
    }

    public function listResults(array $filters = []): Collection
    {
        return Spk::query()
            ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan', 'jenisHukuman'])
            ->when(array_key_exists('user_id', $filters), fn ($query) => $query->where('user_id', $filters['user_id']))
            ->when($filters['active_batch_id'] ?? null, function ($query, $value) use ($filters) {
                return $query
                    ->when(
                        empty($filters['search'])
                            && empty($filters['tanggal'])
                            && empty($filters['tanggal_mulai'])
                            && empty($filters['tanggal_akhir']),
                        fn ($batchQuery) => $batchQuery->where('batch_id', $value)
                    );
            })
            ->when($filters['tanggal'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', $value))
            ->when($filters['tanggal_mulai'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '>=', $value))
            ->when($filters['tanggal_akhir'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '<=', $value))
            ->when($filters['search'] ?? null, function ($query, $value) {
                $query->whereHas('siswa', function ($studentQuery) use ($value) {
                    $studentQuery->where('nama', 'like', "%{$value}%")
                        ->orWhere('nisn', 'like', "%{$value}%");
                });
            })
            ->oldest('tanggal_spk')
            ->oldest('id')
            ->get();
    }

    public function exportDataset(array $filters = []): Collection
    {
        return Spk::query()
            ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan', 'jenisHukuman'])
            ->when(array_key_exists('user_id', $filters), fn ($query) => $query->where('user_id', $filters['user_id']))
            ->when($filters['active_batch_id'] ?? null, function ($query, $value) use ($filters) {
                return $query
                    ->when(
                        empty($filters['search'])
                            && empty($filters['tanggal'])
                            && empty($filters['tanggal_mulai'])
                            && empty($filters['tanggal_akhir']),
                        fn ($batchQuery) => $batchQuery->where('batch_id', $value)
                    );
            })
            ->when($filters['tanggal'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', $value))
            ->when($filters['tanggal_mulai'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '>=', $value))
            ->when($filters['tanggal_akhir'] ?? null, fn ($query, $value) => $query->whereDate('tanggal_spk', '<=', $value))
            ->when($filters['search'] ?? null, function ($query, $value) {
                $query->whereHas('siswa', function ($studentQuery) use ($value) {
                    $studentQuery->where('nama', 'like', "%{$value}%")
                        ->orWhere('nisn', 'like', "%{$value}%");
                });
            })
            ->oldest('tanggal_spk')
            ->oldest('id')
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

    private function hukumanForFinalValue(float $finalValue): ?JenisHukuman
    {
        $hukumans = JenisHukuman::query()->oldest('id')->get();

        return $hukumans->first(fn (JenisHukuman $hukuman) => $hukuman->containsFinalValue($finalValue));
    }
}
