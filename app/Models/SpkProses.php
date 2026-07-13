<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpkProses extends Model
{
    protected $table = 'spk_proses';

    protected $fillable = [
        'tanggal_proses',
        'kode_proses',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_proses' => 'date',
        ];
    }

    public function hasils()
    {
        return $this->hasMany(SpkProsesHasil::class, 'spk_proses_id');
    }
}
