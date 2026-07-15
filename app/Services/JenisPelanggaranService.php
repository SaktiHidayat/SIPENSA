<?php

namespace App\Services;

use App\Models\JenisPelanggaran;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JenisPelanggaranService
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $search = trim((string) $search);

        return JenisPelanggaran::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_pelanggaran', 'like', "%{$search}%")
                        ->orWhere('nama_pelanggaran', 'like', "%{$search}%")
                        ->orWhere('poin_pelanggaran', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): JenisPelanggaran
    {
        return JenisPelanggaran::create([
            'kode_pelanggaran' => $data['kode_pelanggaran'],
            'nama_pelanggaran' => $data['nama_pelanggaran'],
            'poin_pelanggaran' => $data['poin_pelanggaran'],
        ]);
    }

    public function update(JenisPelanggaran $jenisPelanggaran, array $data): JenisPelanggaran
    {
        $jenisPelanggaran->newQuery()
            ->whereKey($jenisPelanggaran->id)
            ->update(array_filter([
                'kode_pelanggaran' => $data['kode_pelanggaran'] ?? $jenisPelanggaran->kode_pelanggaran,
                'nama_pelanggaran' => $data['nama_pelanggaran'] ?? $jenisPelanggaran->nama_pelanggaran,
                'poin_pelanggaran' => $data['poin_pelanggaran'] ?? $jenisPelanggaran->poin_pelanggaran,
            ], static fn ($value) => $value !== null));

        return $jenisPelanggaran;
    }

    public function delete(JenisPelanggaran $jenisPelanggaran): void
    {
        $jenisPelanggaran->newQuery()
            ->whereKey($jenisPelanggaran->id)
            ->delete();
    }

    public function fuzzyRules(): array
    {
        return JenisPelanggaran::fuzzyRules();
    }
}
