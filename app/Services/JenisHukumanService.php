<?php

namespace App\Services;

use App\Models\JenisHukuman;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JenisHukumanService
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $search = trim((string) $search);

        return JenisHukuman::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_hukuman', 'like', "%{$search}%")
                        ->orWhere('nama_hukuman', 'like', "%{$search}%")
                        ->orWhere('rentang_poin', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): JenisHukuman
    {
        return JenisHukuman::create([
            'kode_hukuman' => $data['kode_hukuman'],
            'nama_hukuman' => $data['nama_hukuman'],
            'rentang_poin' => $data['rentang_poin'],
        ]);
    }

    public function update(JenisHukuman $jenisHukuman, array $data): JenisHukuman
    {
        $jenisHukuman->update([
            'kode_hukuman' => $data['kode_hukuman'],
            'nama_hukuman' => $data['nama_hukuman'],
            'rentang_poin' => $data['rentang_poin'],
        ]);

        return $jenisHukuman;
    }

    public function delete(JenisHukuman $jenisHukuman): void
    {
        $jenisHukuman->delete();
    }
}
