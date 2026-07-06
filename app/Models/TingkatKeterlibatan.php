<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatKeterlibatan extends Model
{
    protected $fillable = [
        'kode_tingkat_keterlibatan',
        'nama_keterangan',
        'poin_keterlibatan',
        'kategori',
        'skala_fuzzy',
    ];

    protected function casts(): array
    {
        return [
            'poin_keterlibatan' => 'integer',
            'skala_fuzzy' => 'decimal:2',
        ];
    }

    public static function fuzzyRules(): array
    {
        return [
            [
                'label' => 'Hanya diri sendiri',
                'point' => 0,
                'min' => 0,
                'max' => 4,
                'kategori' => 'Sangat Rendah',
                'rumus' => '0/(5-1)=0/4=0',
                'skala_fuzzy' => 0,
            ],
            [
                'label' => 'Melibatkan siswa',
                'point' => 5,
                'min' => 5,
                'max' => 15,
                'kategori' => 'Rendah',
                'rumus' => '1/(5-1)=1/4=0.25',
                'skala_fuzzy' => 0.25,
            ],
            [
                'label' => 'Melibatkan guru',
                'point' => 16,
                'min' => 16,
                'max' => 30,
                'kategori' => 'Sedang',
                'rumus' => '2/(5-1)=2/4=0.5',
                'skala_fuzzy' => 0.5,
            ],
            [
                'label' => 'Melibatkan siswa dan guru',
                'point' => 31,
                'min' => 31,
                'max' => 75,
                'kategori' => 'Tinggi',
                'rumus' => '3/(5-1)=3/4=0.75',
                'skala_fuzzy' => 0.75,
            ],
            [
                'label' => 'Melibatkan guru dan pihak sekolah (BK, wali, staff)',
                'point' => 76,
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

    public static function labels(): array
    {
        return array_column(static::fuzzyRules(), 'label');
    }

    public static function pointForLabel(string $label): int
    {
        return static::ruleForLabel($label)['point'];
    }

    public static function randomPointForLabel(string $label): int
    {
        $rule = static::ruleForLabel($label);

        return random_int($rule['min'], $rule['max']);
    }

    public static function ruleForLabel(string $label): array
    {
        foreach (static::fuzzyRules() as $rule) {
            if ($rule['label'] === $label) {
                return $rule;
            }
        }

        return static::fuzzyRules()[0];
    }

    public function getKategoriFuzzyAttribute(): string
    {
        return static::fuzzyFromPoint($this->poin_keterlibatan)['kategori'];
    }

    public function getSkalaFuzzyAttribute(): float
    {
        return static::fuzzyFromPoint($this->poin_keterlibatan)['skala_fuzzy'];
    }

    public function getRumusFuzzyAttribute(): string
    {
        return static::fuzzyFromPoint($this->poin_keterlibatan)['rumus'];
    }

    public function getRentangPoinAttribute(): string
    {
        $rule = static::fuzzyFromPoint($this->poin_keterlibatan);

        return $rule['min'].' - '.$rule['max'];
    }

    public function getPointOtomatisAttribute(): int
    {
        return $this->poin_keterlibatan;
    }

    public function alternatifs()
    {
        return $this->hasMany(Alternatif::class);
    }
}
