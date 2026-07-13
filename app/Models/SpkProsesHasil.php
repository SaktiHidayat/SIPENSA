<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkProsesHasil extends Model
{
    protected $table = 'spk_proses_hasils';

    protected $fillable = [
        'spk_proses_id',
        'alternatif_id',
        'siswa_id',
        'kode_alternatif',
        'nisn',
        'poin_c1',
        'skala_c1',
        'normalisasi_c1',
        'skala_c2',
        'normalisasi_c2',
        'nilai_akhir',
        'jenis_hukuman_id',
        'kode_hukuman',
        'nama_hukuman',
        'kategori_hukuman',
        'rentang_poin_hukuman',
        'ranking',
    ];

    protected function casts(): array
    {
        return [
            'poin_c1' => 'integer',
            'skala_c1' => 'decimal:2',
            'normalisasi_c1' => 'decimal:4',
            'skala_c2' => 'decimal:2',
            'normalisasi_c2' => 'decimal:4',
            'nilai_akhir' => 'decimal:4',
            'jenis_hukuman_id' => 'integer',
            'ranking' => 'integer',
        ];
    }

    public function spkProses()
    {
        return $this->belongsTo(SpkProses::class, 'spk_proses_id');
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function jenisHukuman()
    {
        return $this->belongsTo(JenisHukuman::class, 'jenis_hukuman_id');
    }
}
