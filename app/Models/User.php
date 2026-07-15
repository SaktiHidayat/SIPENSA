<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'nip',
        'password',
        'jenis_kelamin',
        'role',
        'foto'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return route('profile.photo');
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->nama ?? 'SIPENSA').'&background=111827&color=ffffff';
    }
}
