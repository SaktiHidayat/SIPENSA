<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkKejadian extends Model
{
    protected $table = 'spk_kejadians';

    protected $fillable = [
        'siswa_id',
        'jenis_pelanggaran_id',
        'tingkat_keterlibatan_id',
        'poin_snapshot',
        'tanggal_kejadian',
        'spk_id',
    ];

    protected function casts(): array
    {
        return [
            'poin_snapshot' => 'integer',
            'tanggal_kejadian' => 'date',
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

    public function spk()
    {
        return $this->belongsTo(Spk::class);
    }
}
