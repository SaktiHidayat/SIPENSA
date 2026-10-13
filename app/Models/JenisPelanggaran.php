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

    public function getPoinPelanggaranAttribute(mixed $value): int
    {
        return (int) (str_contains((string) $value, '-') ? str($value)->afterLast('-') : $value);
    }

    public function getPoinPelanggaranRangeAttribute(): string
    {
        $value = (string) $this->getRawOriginal('poin_pelanggaran');

        return $value !== '' ? $value : (string) $this->poin_pelanggaran;
    }

    public static function fuzzyRules(): array
    {
        return [
            [
                'min' => 5,
                'max' => 15,
                'kategori' => 'Sangat Rendah',
                'rumus' => '0/6 = 0',
                'skala_fuzzy' => 0,
            ],
            [
                'min' => 16,
                'max' => 30,
                'kategori' => 'Rendah',
                'rumus' => '1/6 = 0.1667',
                'skala_fuzzy' => 1 / 6,
            ],
            [
                'min' => 31,
                'max' => 50,
                'kategori' => 'Sedang',
                'rumus' => '2/6 = 0.3333',
                'skala_fuzzy' => 2 / 6,
            ],
            [
                'min' => 51,
                'max' => 75,
                'kategori' => 'Sedang Tinggi',
                'rumus' => '3/6 = 0.5',
                'skala_fuzzy' => 3 / 6,
            ],
            [
                'min' => 76,
                'max' => 100,
                'kategori' => 'Tinggi',
                'rumus' => '4/6 = 0.6667',
                'skala_fuzzy' => 4 / 6,
            ],
            [
                'min' => 101,
                'max' => 200,
                'kategori' => 'Sangat Tinggi',
                'rumus' => '5/6 = 0.8333',
                'skala_fuzzy' => 5 / 6,
            ],
            [
                'min' => 201,
                'max' => 300,
                'kategori' => 'Ekstrem',
                'rumus' => '6/6 = 1',
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
        return $this->poin_pelanggaran_range;
    }
    
}
