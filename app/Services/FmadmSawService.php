<?php

namespace App\Services;

use Closure;

class FmadmSawService
{
    public function fuzzyRules(): array
    {
        return [
            ['min' => 5, 'max' => 15, 'kategori' => 'Sangat Rendah', 'rumus' => '0/6 = 0', 'skala_fuzzy' => 0],
            ['min' => 16, 'max' => 30, 'kategori' => 'Rendah', 'rumus' => '1/6 = 0.1667', 'skala_fuzzy' => 1 / 6],
            ['min' => 31, 'max' => 50, 'kategori' => 'Sedang', 'rumus' => '2/6 = 0.3333', 'skala_fuzzy' => 2 / 6],
            ['min' => 51, 'max' => 75, 'kategori' => 'Sedang Tinggi', 'rumus' => '3/6 = 0.5', 'skala_fuzzy' => 3 / 6],
            ['min' => 76, 'max' => 100, 'kategori' => 'Tinggi', 'rumus' => '4/6 = 0.6667', 'skala_fuzzy' => 4 / 6],
            ['min' => 101, 'max' => 200, 'kategori' => 'Sangat Tinggi', 'rumus' => '5/6 = 0.8333', 'skala_fuzzy' => 5 / 6],
            ['min' => 201, 'max' => 300, 'kategori' => 'Ekstrem', 'rumus' => '6/6 = 1', 'skala_fuzzy' => 1],
        ];
    }

    public function fuzzifyValue(float $rawValue): float
    {
        foreach ($this->fuzzyRules() as $rule) {
            if ($rawValue >= $rule['min'] && $rawValue <= $rule['max']) {
                return (float) $rule['skala_fuzzy'];
            }
        }

        return 0.0;
    }

    public function calculate(array $rows, array $weights, ?Closure $sanctionResolver = null): array
    {
        $weightC1 = (float) ($weights[0] ?? 0.0);
        $weightC2 = (float) ($weights[1] ?? 0.0);

        $fuzzyRows = array_map(function (array $row): array {
            $rawC1 = (float) ($row['poin_c1'] ?? 0.0);

            return array_merge($row, [
                'skala_c1' => $this->fuzzifyValue($rawC1),
                'skala_c2' => (float) ($row['skala_c2'] ?? 0.0),
            ]);
        }, $rows);

        $maxC1 = max(array_column($fuzzyRows, 'skala_c1') ?: [0.0]);
        $maxC2 = max(array_column($fuzzyRows, 'skala_c2') ?: [0.0]);

        if (count($rows) < 5) {
            $crispMaximum = 3.0;
            $maxC1 = max($maxC1, $crispMaximum);
            $maxC2 = max($maxC2, $crispMaximum);
        }

        $results = array_map(function (array $row) use ($maxC1, $maxC2, $weightC1, $weightC2, $sanctionResolver): array {
            $normalC1 = $this->normalizeBenefit((float) $row['skala_c1'], (float) $maxC1);
            $normalC2 = $this->normalizeBenefit((float) $row['skala_c2'], (float) $maxC2);
            $contributionC1 = $normalC1 * $weightC1;
            $contributionC2 = $normalC2 * $weightC2;
            $score = $contributionC1 + $contributionC2;
            $sanction = $sanctionResolver ? $sanctionResolver($score) : null;

            return array_merge($row, [
                'normalisasi_c1' => $normalC1,
                'normalisasi_c2' => $normalC2,
                'bobot_c1' => $weightC1,
                'bobot_c2' => $weightC2,
                'kontribusi_c1' => $contributionC1,
                'kontribusi_c2' => $contributionC2,
                'nilai_akhir' => $score,
                'hukuman' => $sanction?->nama_hukuman,
                'rentang_poin_hukuman' => $sanction?->rentang_poin,
            ]);
        }, $fuzzyRows);

        usort($results, fn (array $left, array $right): int => $right['nilai_akhir'] <=> $left['nilai_akhir']);

        return array_values($results);
    }

    private function normalizeBenefit(float $value, float $maxValue): float
    {
        return $maxValue > 0 ? $value / $maxValue : 0.0;
    }
}