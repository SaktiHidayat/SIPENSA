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
                'rumus' => '0/(5-1)=0/4=0',
                'skala_fuzzy' => 0,
            ],
            [
                'min' => 16,
                'max' => 30,
                'kategori' => 'Rendah',
                'rumus' => '1/(5-1)=1/4=0.25',
                'skala_fuzzy' => 0.25,
            ],
            [
                'min' => 50,
                'max' => 75,
                'kategori' => 'Sedang',
                'rumus' => '2/(5-1)=2/4=0.5',
                'skala_fuzzy' => 0.5,
            ],
            [
                'min' => 100,
                'max' => 100,
                'kategori' => 'Tinggi',
                'rumus' => '3/(5-1)=3/4=0.75',
                'skala_fuzzy' => 0.75,
            ],
            [
                'min' => 300,
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
        return $this->poin_pelanggaran_range;
    }

    public function alternatifs()
    {
        return $this->hasMany(Alternatif::class);
    }
}
