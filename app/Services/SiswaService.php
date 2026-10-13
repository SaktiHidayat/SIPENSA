<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SiswaService
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $search = trim((string) $search);
        $normalizedSearch = strtolower($search);

        return Siswa::query()
            ->when($search, function ($query) use ($search, $normalizedSearch) {
                $query->where(function ($query) use ($search, $normalizedSearch) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('kelas', 'like', "%{$search}%")
                        ->orWhere('jenis_kelamin', 'like', "%{$search}%");

                    if (str_contains($normalizedSearch, 'laki')) {
                        $query->orWhere('jenis_kelamin', 'L');
                    }

                    if (str_contains($normalizedSearch, 'perempuan')) {
                        $query->orWhere('jenis_kelamin', 'P');
                    }
                });
            })
            ->oldest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Siswa
    {
        return Siswa::create([
            'nama' => $data['nama'],
            'nisn' => $data['nisn'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'kelas' => $data['kelas'],
            'alamat' => $data['alamat'],
        ]);
    }

    public function update(Siswa $siswa, array $data): Siswa
    {
        $siswa->update([
            'nama' => $data['nama'],
            'nisn' => $data['nisn'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'kelas' => $data['kelas'],
            'alamat' => $data['alamat'],
        ]);

        return $siswa;
    }

    public function delete(Siswa $siswa): void
    {
        $siswa->delete();
    }
}
