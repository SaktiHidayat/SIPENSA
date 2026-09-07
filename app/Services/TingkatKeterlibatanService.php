<?php

namespace App\Services;

use App\Models\TingkatKeterlibatan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TingkatKeterlibatanService
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $search = trim((string) $search);

        return TingkatKeterlibatan::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_tingkat_keterlibatan', 'like', "%{$search}%")
                        ->orWhere('nama_keterangan', 'like', "%{$search}%")
                        ->orWhere('skala_fuzzy', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): TingkatKeterlibatan
    {
        $rule = TingkatKeterlibatan::ruleForLabel($data['nama_keterangan']);

        return TingkatKeterlibatan::create([
            'kode_tingkat_keterlibatan' => $data['kode_tingkat_keterlibatan'],
            'nama_keterangan' => $data['nama_keterangan'],
            'skala_fuzzy' => $rule['skala_fuzzy'],
        ]);
    }

    public function update(TingkatKeterlibatan $tingkatKeterlibatan, array $data): TingkatKeterlibatan
    {
        $rule = TingkatKeterlibatan::ruleForLabel($data['nama_keterangan']);

        $tingkatKeterlibatan->update([
            'kode_tingkat_keterlibatan' => $data['kode_tingkat_keterlibatan'],
            'nama_keterangan' => $data['nama_keterangan'],
            'skala_fuzzy' => $rule['skala_fuzzy'],
        ]);

        return $tingkatKeterlibatan;
    }

    public function delete(TingkatKeterlibatan $tingkatKeterlibatan): void
    {
        $tingkatKeterlibatan->delete();
    }

    public function fuzzyRules(): array
    {
        return TingkatKeterlibatan::fuzzyRules();
    }
}
