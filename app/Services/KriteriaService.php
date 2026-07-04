<?php

namespace App\Services;

use App\Models\Kriteria;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class KriteriaService
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $search = trim((string) $search);

        return Kriteria::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_kriteria', 'like', "%{$search}%")
                        ->orWhere('nama_kriteria', 'like', "%{$search}%")
                        ->orWhere('bobot', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Kriteria
    {
        return Kriteria::create([
            'kode_kriteria' => $data['kode_kriteria'],
            'nama_kriteria' => $data['nama_kriteria'],
            'bobot' => $data['bobot'],
        ]);
    }

    public function update(Kriteria $kriteria, array $data): Kriteria
    {
        $kriteria->update([
            'kode_kriteria' => $data['kode_kriteria'],
            'nama_kriteria' => $data['nama_kriteria'],
            'bobot' => $data['bobot'],
        ]);

        return $kriteria;
    }

    public function delete(Kriteria $kriteria): void
    {
        $kriteria->delete();
    }
}
