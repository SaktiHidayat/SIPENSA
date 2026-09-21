<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spk extends Model
{
    protected $table = 'spks';

    protected $fillable = [
        'kode_spk',
        'user_id',
        'batch_id',
        'tanggal_spk',
        'siswa_id',
        'jenis_pelanggaran_id',
        'tingkat_keterlibatan_id',
        'poin_jenis_pelanggaran',
        'poin_sebelumnya',
        'poin_sekarang',
        'total_poin',
        'skala_jenis_pelanggaran',
        'skala_tingkat_keterlibatan',
        'normalisasi_c1',
        'normalisasi_c2',
        'nilai_akhir',
        'nilai_akhir_sebelumnya',
        'nilai_akhir_sekarang',
        'total_nilai_akhir',
        'jenis_hukuman_id',
        'kode_hukuman',
        'nama_hukuman',
        'rentang_poin_hukuman',
        'alternatif_snapshot',
        'alternatif_snapshot_history',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_spk' => 'date',
            'batch_id' => 'string',
            'poin_jenis_pelanggaran' => 'integer',
            'poin_sebelumnya' => 'integer',
            'poin_sekarang' => 'integer',
            'total_poin' => 'integer',
            'skala_jenis_pelanggaran' => 'decimal:2',
            'skala_tingkat_keterlibatan' => 'decimal:2',
            'normalisasi_c1' => 'decimal:4',
            'normalisasi_c2' => 'decimal:4',
            'nilai_akhir' => 'decimal:4',
            'nilai_akhir_sebelumnya' => 'decimal:4',
            'nilai_akhir_sekarang' => 'decimal:4',
            'total_nilai_akhir' => 'decimal:4',
            'jenis_hukuman_id' => 'integer',
            'alternatif_snapshot' => 'array',
            'alternatif_snapshot_history' => 'array',
        ];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
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

    public function jenisHukuman()
    {
        return $this->belongsTo(JenisHukuman::class, 'jenis_hukuman_id');
    }

    public function spkKejadians()
    {
        return $this->hasMany(SpkKejadian::class);
    }
}
