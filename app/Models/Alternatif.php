<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatifs';

    protected $fillable = [
        'kode_alternatif',
        'siswa_id',
        'nisn',
        'jenis_pelanggaran_id',
        'poin_jenis_pelanggaran',
        'skala_fuzzy_jenis_pelanggaran',
        'tingkat_keterlibatan_id',
        'skala_fuzzy_tingkat_keterlibatan',
    ];

    protected function casts(): array
    {
        return [
            'poin_jenis_pelanggaran' => 'integer',
            'skala_fuzzy_jenis_pelanggaran' => 'decimal:2',
            'skala_fuzzy_tingkat_keterlibatan' => 'decimal:2',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class);
    }

    public function tingkatKeterlibatan()
    {
        return $this->belongsTo(TingkatKeterlibatan::class);
    }
}
