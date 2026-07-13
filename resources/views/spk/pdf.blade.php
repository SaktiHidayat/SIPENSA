<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        .muted { color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background: #f9fafb; }
    </style>
</head>
<body>
    <h1>Hasil SPK</h1>
    <div class="muted">
        Cetak:
        @if ($tanggal)
            Berdasarkan tanggal
            <br>
            Tanggal: {{ $tanggal ?: '-' }}
        @else
            Semua data
        @endif
    </div>

    @php
        $formatNumber = function ($value, int $precision = 4) {
            if ($value === null || $value === '') {
                return '0';
            }

            $formatted = number_format((float) $value, $precision, '.', '');

            return rtrim(rtrim($formatted, '0'), '.');
        };
    @endphp

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>C1</th>
                <th>C2</th>
                <th>Nilai Akhir</th>
                <th>Hukuman</th>
                <th>C1 Alternatif</th>
                <th>C2 Alternatif</th>
                <th>Nilai Akhir Alternatif</th>
                <th>Hukuman Alternatif</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $spk)
                @php
                    $alternatifSnapshots = $spk->alternatif_snapshot ?: [];
                    if (empty($alternatifSnapshots)) {
                        $alternatifSnapshots = [[
                            'skala_c1' => 0,
                            'skala_c2' => 0,
                            'nilai_akhir' => 0,
                            'hukuman' => 'Tidak ada',
                        ]];
                    }

                    $alternatifC1 = collect($alternatifSnapshots)->map(fn ($alt) => $formatNumber($alt['skala_c1'] ?? 0, 2))->implode(', ');
                    $alternatifC2 = collect($alternatifSnapshots)->map(fn ($alt) => $formatNumber($alt['skala_c2'] ?? 0, 2))->implode(', ');
                    $alternatifNilaiAkhir = collect($alternatifSnapshots)->map(fn ($alt) => $formatNumber($alt['nilai_akhir'] ?? 0, 4))->implode(', ');
                    $alternatifHukuman = collect($alternatifSnapshots)->map(fn ($alt) => $alt['hukuman'] ?? 'Tidak ada')->implode(', ');
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $spk->siswa?->nama ?? '-' }}</td>
                    <td>{{ $spk->siswa?->nisn ?? '-' }}</td>
                    <td>{{ $formatNumber($spk->skala_jenis_pelanggaran, 2) }}</td>
                    <td>{{ $formatNumber($spk->skala_tingkat_keterlibatan, 2) }}</td>
                    <td>{{ $formatNumber($spk->nilai_akhir, 4) }}</td>
                    <td>{{ $spk->nama_hukuman ?? $spk->jenisHukuman?->nama_hukuman ?? '-' }}</td>
                    <td>{{ $alternatifC1 }}</td>
                    <td>{{ $alternatifC2 }}</td>
                    <td>{{ $alternatifNilaiAkhir }}</td>
                    <td>{{ $alternatifHukuman }}</td>
                </tr>
            @empty
                <tr><td colspan="11">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
