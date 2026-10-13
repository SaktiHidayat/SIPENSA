<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nisn',
        'jenis_kelamin',
        'kelas',
        'alamat',
    ];

    public function spkKejadians()
    {
        return $this->hasMany(SpkKejadian::class);
    }

    public function spkCalonSiswas()
    {
        return $this->hasMany(SpkCalonSiswa::class);
    }
}
