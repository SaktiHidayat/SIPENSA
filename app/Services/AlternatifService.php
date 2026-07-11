<?php

namespace App\Services;

use App\Models\Alternatif;
use App\Models\JenisPelanggaran;
use App\Models\Siswa;
use App\Models\TingkatKeterlibatan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AlternatifService
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $search = trim((string) $search);

        return Alternatif::query()
            ->with(['siswa', 'jenisPelanggaran', 'tingkatKeterlibatan'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_alternatif', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhereHas('siswa', function ($query) use ($search) {
                            $query->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('jenisPelanggaran', function ($query) use ($search) {
                            $query->where('nama_pelanggaran', 'like', "%{$search}%")
                                ->orWhere('kode_pelanggaran', 'like', "%{$search}%");
                        })
                        ->orWhereHas('tingkatKeterlibatan', function ($query) use ($search) {
                            $query->where('nama_keterangan', 'like', "%{$search}%")
                                ->orWhere('kode_tingkat_keterlibatan', 'like', "%{$search}%");
                        });
                });
            })
            ->oldest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Alternatif
    {
        $siswa = Siswa::findOrFail($data['siswa_id']);
        $jenisPelanggaran = JenisPelanggaran::findOrFail($data['jenis_pelanggaran_id']);
        $tingkatKeterlibatan = TingkatKeterlibatan::findOrFail($data['tingkat_keterlibatan_id']);

        return Alternatif::create([
            'kode_alternatif' => $data['kode_alternatif'],
            'siswa_id' => $siswa->id,
            'nisn' => $siswa->nisn,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'poin_jenis_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
            'skala_fuzzy_jenis_pelanggaran' => $jenisPelanggaran->skala_fuzzy,
            'tingkat_keterlibatan_id' => $tingkatKeterlibatan->id,
            'skala_fuzzy_tingkat_keterlibatan' => $tingkatKeterlibatan->skala_fuzzy,
        ]);
    }

    public function update(Alternatif $alternatif, array $data): Alternatif
    {
        $siswa = Siswa::findOrFail($data['siswa_id']);
        $jenisPelanggaran = JenisPelanggaran::findOrFail($data['jenis_pelanggaran_id']);
        $tingkatKeterlibatan = TingkatKeterlibatan::findOrFail($data['tingkat_keterlibatan_id']);

        $alternatif->update([
            'kode_alternatif' => $data['kode_alternatif'],
            'siswa_id' => $siswa->id,
            'nisn' => $siswa->nisn,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'poin_jenis_pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
            'skala_fuzzy_jenis_pelanggaran' => $jenisPelanggaran->skala_fuzzy,
            'tingkat_keterlibatan_id' => $tingkatKeterlibatan->id,
            'skala_fuzzy_tingkat_keterlibatan' => $tingkatKeterlibatan->skala_fuzzy,
        ]);

        return $alternatif;
    }

    public function delete(Alternatif $alternatif): void
    {
        $alternatif->delete();
    }
}
