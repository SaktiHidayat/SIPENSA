<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $search = trim((string) $search);
        $normalizedSearch = strtolower($search);

        return User::query()
            ->when($search, function ($query) use ($search, $normalizedSearch) {
                $query->where(function ($query) use ($search, $normalizedSearch) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('jenis_kelamin', 'like', "%{$search}%");

                    if (str_contains($normalizedSearch, 'admin')) {
                        $query->orWhere('role', 'admin');
                    }

                    if (str_contains($normalizedSearch, 'guru') || str_contains($normalizedSearch, 'bk')) {
                        $query->orWhere('role', 'guru_bk');
                    }

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

    public function create(array $data): User
    {
        return User::create([
            'nama' => $data['nama'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'jenis_kelamin' => $data['jenis_kelamin'],
            'role' => $data['role'],
        ]);
    }

    public function update(User $user, array $data): User
    {
        $payload = [
            'nama' => $data['nama'],
            'username' => $data['username'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'role' => $data['role'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
