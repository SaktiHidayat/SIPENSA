<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TingkatKeterlibatan extends Model
{
    protected $fillable = [
        'kode_tingkat_keterlibatan',
        'nama_keterangan',
        'skala_fuzzy',
    ];

    protected function casts(): array
    {
        return [
            'skala_fuzzy' => 'decimal:2',
        ];
    }

    public static function fuzzyRules(): array
    {
        return [
            [
                'label' => 'Hanya diri sendiri',
                'point' => 1,
                'order' => 1,
                'kategori' => 'Sangat Rendah',
                'rumus' => '0/(5-1)=0/4=0',
                'skala_fuzzy' => 0,
            ],
            [
                'label' => 'Melibatkan siswa',
                'point' => 2,
                'order' => 2,
                'kategori' => 'Rendah',
                'rumus' => '2/(5-1)=2/4=0.25',
                'skala_fuzzy' => 0.25,
            ],
            [
                'label' => 'Melibatkan guru',
                'point' => 3,
                'order' => 3,
                'kategori' => 'Sedang',
                'rumus' => '3/(5-1)=3/4=0.5',
                'skala_fuzzy' => 0.5,
            ],
            [
                'label' => 'Melibatkan siswa dan guru',
                'point' => 4,
                'order' => 4,
                'kategori' => 'Tinggi',
                'rumus' => '4/(5-1)=4/4=0.75',
                'skala_fuzzy' => 0.75,
            ],
            [
                'label' => 'Melibatkan guru dan pihak sekolah (BK, wali, staff)',
                'point' => 5,
                'order' => 5,
                'kategori' => 'Sangat Tinggi',
                'rumus' => '4/(5-1)=4/4=1',
                'skala_fuzzy' => 1,
            ],
        ];
    }

    public static function labels(): array
    {
        return array_column(static::fuzzyRules(), 'label');
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
        return static::ruleForLabel($this->nama_keterangan)['kategori'];
    }

    public function getSkalaFuzzyAttribute(): float
    {
        return static::ruleForLabel($this->nama_keterangan)['skala_fuzzy'];
    }

    public function getSkalaFuzzyRawAttribute(): float
    {
        return (float) ($this->attributes['skala_fuzzy'] ?? 0);
    }

    public function getRumusFuzzyAttribute(): string
    {
        return static::ruleForLabel($this->nama_keterangan)['rumus'];
    }
    
}
