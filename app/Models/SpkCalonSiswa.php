<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkCalonSiswa extends Model
{
    protected $table = 'spk_calon_siswas';

    protected $fillable = [
        'user_id',
        'nama',
        'kelas',
        'jenis_pelanggaran_id',
        'tingkat_keterlibatan_id',
        'siswa_id',
        'diproses_pada',
    ];

    protected function casts(): array
    {
        return ['diproses_pada' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class);
    }

    public function tingkatKeterlibatan()
    {
        return $this->belongsTo(TingkatKeterlibatan::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}