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
        @if (($tanggal ?? '') || ($tanggal_mulai ?? '') || ($tanggal_akhir ?? '') || ($search ?? ''))
            Berdasarkan filter
            <br>
            @if ($tanggal ?? '')
                Tanggal: {{ $tanggal }}
            @elseif (($tanggal_mulai ?? '') || ($tanggal_akhir ?? ''))
                Tanggal: {{ $tanggal_mulai ?: 'awal' }} sampai {{ $tanggal_akhir ?: 'akhir' }}
            @endif
            @if ($search ?? '')
                Nama/NISN: {{ $search }}
            @endif
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
                <th>Total Nilai Akhir</th>
                <th>Hukuman</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($results as $spk)
                @php
                @endphp

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $spk->siswa?->nama ?? '-' }}</td>
                    <td>{{ $spk->siswa?->nisn ?? '-' }}</td>
                    <td>{{ $spk->jenisPelanggaran?->nama_pelanggaran ?? '-' }} ({{ $formatNumber($spk->skala_jenis_pelanggaran, 2) }})</td>
                    <td>{{ $spk->tingkatKeterlibatan?->nama_keterangan ?? '-' }} ({{ $formatNumber($spk->skala_tingkat_keterlibatan, 2) }})</td>
                    <td>{{ $formatNumber($spk->total_nilai_akhir, 4) }}</td>
                    <td>{{ $spk->nama_hukuman ?? $spk->jenisHukuman?->nama_hukuman ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
