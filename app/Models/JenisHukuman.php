<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHukuman extends Model
{
    protected $table = 'jenis_hukumans';

    protected $fillable = [
        'kode_hukuman',
        'nama_hukuman',
        'rentang_poin',
    ];

    public function getRentangPoinDisplayAttribute(): string
    {
        return (string) $this->rentang_poin;
    }

    public function containsFinalValue(float $finalValue): bool
    {
        if (preg_match('/^\s*(\d+(?:\.\d+)?)\s*-\s*(\d+(?:\.\d+)?)(?:\+)?\s*$/', (string) $this->rentang_poin, $matches)) {
            $minimum = (float) $matches[1];
            $maximum = (float) $matches[2];

            return $finalValue >= $minimum && $finalValue <= $maximum;
        }

        return is_numeric($this->rentang_poin) && $finalValue === (float) $this->rentang_poin;
    }

}
