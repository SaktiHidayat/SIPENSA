<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisHukuman extends Model
{
    protected $table = 'jenis_hukumans';

    protected $fillable = [
        'kode_hukuman',
        'nama_hukuman',
        'kategori',
        'rentang_poin',
    ];

    public static function categories(): array
    {
        return [
            'sangat rendah' => 'Sangat Rendah',
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
            'sangat tinggi' => 'Sangat Tinggi',
        ];
    }

    public static function ranges(): array
    {
        return [
            'sangat rendah' => '0 - 0.15',
            'rendah' => '0.16 - 0.3',
            'sedang' => '0.31 - 0.55',
            'tinggi' => '0.56 - 0.8',
            'sangat tinggi' => '0.81 - 1',
        ];
    }

    public static function rangeForCategory(?string $kategori): string
    {
        return static::ranges()[$kategori] ?? '';
    }

    public function getRentangPoinDisplayAttribute(): string
    {
        return $this->rentang_poin ?: static::rangeForCategory($this->kategori);
    }

    public static function ruleForScore(float $score): array
    {
        if ($score <= 0.15) {
            return [
                'kategori' => 'Sangat Rendah',
                'rentang_poin' => '0 - 0.15',
            ];
        }

        if ($score <= 0.3) {
            return [
                'kategori' => 'Rendah',
                'rentang_poin' => '0.16 - 0.3',
            ];
        }

        if ($score <= 0.55) {
            return [
                'kategori' => 'Sedang',
                'rentang_poin' => '0.31 - 0.55',
            ];
        }

        if ($score <= 0.8) {
            return [
                'kategori' => 'Tinggi',
                'rentang_poin' => '0.56 - 0.8',
            ];
        }

        return [
            'kategori' => 'Sangat Tinggi',
            'rentang_poin' => '0.81 - 1',
        ];
    }
}
