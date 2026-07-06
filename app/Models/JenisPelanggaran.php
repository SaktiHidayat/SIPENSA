<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    protected $fillable = [
        'kode_pelanggaran',
        'nama_pelanggaran',
        'poin_pelanggaran',
    ];

    protected function casts(): array
    {
        return [
            'poin_pelanggaran' => 'integer',
        ];
    }

    public static function fuzzyRules(): array
    {
        return [
            [
                'min' => 0,
                'max' => 4,
                'kategori' => 'Sangat Rendah',
                'rumus' => '0/(5-1)=0/4=0',
                'skala_fuzzy' => 0,
            ],
            [
                'min' => 5,
                'max' => 15,
                'kategori' => 'Rendah',
                'rumus' => '1/(5-1)=1/4=0.25',
                'skala_fuzzy' => 0.25,
            ],
            [
                'min' => 16,
                'max' => 30,
                'kategori' => 'Sedang',
                'rumus' => '2/(5-1)=2/4=0.5',
                'skala_fuzzy' => 0.5,
            ],
            [
                'min' => 31,
                'max' => 75,
                'kategori' => 'Tinggi',
                'rumus' => '3/(5-1)=3/4=0.75',
                'skala_fuzzy' => 0.75,
            ],
            [
                'min' => 76,
                'max' => 300,
                'kategori' => 'Sangat Tinggi',
                'rumus' => '4/(5-1)=4/4=1',
                'skala_fuzzy' => 1,
            ],
        ];
    }

    public static function fuzzyFromPoint(int $point): array
    {
        foreach (static::fuzzyRules() as $rule) {
            if ($point >= $rule['min'] && $point <= $rule['max']) {
                return $rule;
            }
        }

        return static::fuzzyRules()[0];
    }

    public function getKategoriFuzzyAttribute(): string
    {
        return static::fuzzyFromPoint($this->poin_pelanggaran)['kategori'];
    }

    public function getSkalaFuzzyAttribute(): float
    {
        return static::fuzzyFromPoint($this->poin_pelanggaran)['skala_fuzzy'];
    }

    public function getRumusFuzzyAttribute(): string
    {
        return static::fuzzyFromPoint($this->poin_pelanggaran)['rumus'];
    }

    public function getRentangPoinAttribute(): string
    {
        $rule = static::fuzzyFromPoint($this->poin_pelanggaran);

        return $rule['min'].' - '.$rule['max'];
    }

    public function alternatifs()
    {
        return $this->hasMany(Alternatif::class);
    }
}
