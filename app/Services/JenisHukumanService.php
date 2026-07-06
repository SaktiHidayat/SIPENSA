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
                    $query->where('kategori', 'like', "%{$search}%")
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
            'kategori' => $data['kategori'],
            'rentang_poin' => JenisHukuman::rangeForCategory($data['kategori']),
        ]);
    }

    public function update(JenisHukuman $jenisHukuman, array $data): JenisHukuman
    {
        $jenisHukuman->update([
            'kategori' => $data['kategori'],
            'rentang_poin' => JenisHukuman::rangeForCategory($data['kategori']),
        ]);

        return $jenisHukuman;
    }

    public function delete(JenisHukuman $jenisHukuman): void
    {
        $jenisHukuman->delete();
    }
}
