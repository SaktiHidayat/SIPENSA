@extends('layouts.app')

@section('title', 'Data SPK')

@section('content')
    @php
        $spks = $spks ?? collect();
        $searchTanggal = $searchTanggal ?? request('tanggal', '');
        $latestSpk = $spks->first();
        $formatNumber = function ($value, int $precision = 4) {
            if ($value === null || $value === '') {
                return '0';
            }

            $formatted = number_format((float) $value, $precision, '.', '');

            return rtrim(rtrim($formatted, '0'), '.');
        };
        $hukumanBadgeClasses = [
            'sangat rendah' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'rendah' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'sedang' => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
            'tinggi' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'sangat tinggi' => 'bg-red-50 text-red-700 ring-red-200',
        ];
        $pelanggaranBadgeClasses = [
            'sangat rendah' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'rendah' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'sedang' => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
            'tinggi' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'sangat tinggi' => 'bg-red-50 text-red-700 ring-red-200',
        ];
        $keterlibatanBadgeClasses = [
            'sangat rendah' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'rendah' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'sedang' => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
            'tinggi' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'sangat tinggi' => 'bg-red-50 text-red-700 ring-red-200',
        ];
    @endphp

    <div class="space-y-6">
        <x-ui.section-card
            title="Data SPK"
            subtitle="Ringkasan hasil SPK terbaru untuk siswa yang dipilih."
        >
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-4xl space-y-3">
                    @if ($latestSpk)
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gray-600">
                                Hasil SPK Terkini
                            </span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $latestSpk->siswa?->nama ?? '-' }}
                            </span>
                            <span class="text-sm text-gray-500">
                                NISN {{ $latestSpk->siswa?->nisn ?? '-' }}
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">C1 (Jenis Pelanggaran)</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $pelanggaranBadgeClasses[strtolower($latestSpk->jenisPelanggaran?->kategori_fuzzy ?? '')] ?? 'bg-zinc-100 text-zinc-700 ring-zinc-200' }}">
                                        {{ $latestSpk->jenisPelanggaran?->nama_pelanggaran ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">C2 (Tingkat Keterlibatan)</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $keterlibatanBadgeClasses[strtolower($latestSpk->tingkatKeterlibatan?->kategori ?? '')] ?? 'bg-zinc-100 text-zinc-700 ring-zinc-200' }}">
                                        {{ $latestSpk->tingkatKeterlibatan?->nama_keterangan ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Hukuman</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $hukumanBadgeClasses[strtolower($latestSpk->kategori_hukuman ?? '')] ?? 'bg-zinc-100 text-zinc-700 ring-zinc-200' }}">
                                        {{ $latestSpk->nama_hukuman ?? $latestSpk->jenisHukuman?->nama_hukuman ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-600">
                            Belum ada hasil SPK terbaru. Silakan gunakan form proses untuk menghitung sanksi siswa.
                        </p>
                    @endif
                </div>

                <x-ui.action-button type="button" x-data x-on:click="$dispatch('open-modal', 'proses-spk')">
                    Proses
                </x-ui.action-button>
            </div>
        </x-ui.section-card>

        <form action="{{ route('spk.index') }}" method="GET" class="grid gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm lg:grid-cols-[1fr_auto] lg:items-end">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700" for="tanggal">Tanggal</label>
                <input id="tanggal" type="date" name="tanggal" value="{{ $searchTanggal }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700">
            </div>
            <div class="flex gap-2">
                <x-ui.action-button type="submit">Cari</x-ui.action-button>
                <a href="{{ route('spk.index') }}">
                    <x-ui.action-button variant="secondary" type="button">Reset</x-ui.action-button>
                </a>
            </div>
        </form>

        <x-ui.section-card title="Hasil SPK" subtitle="Data disimpan di tabel spks.">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm text-gray-500">
                    Cetak PDF mengikuti tanggal yang sedang dicari. Kalau tanggal kosong, semua data akan dicetak.
                </p>
                <x-ui.print-pdf-button :href="route('spk.pdf', request()->only(['tanggal']))" />
            </div>

            <x-ui.datatable
            :columns="['No', 'Nama', 'NISN', 'C1', 'C2', 'Hukuman', 'Aksi']"
                :items="$spks"
                empty-message="Tidak ada data."
            >
                @foreach ($spks as $spk)
                    <tr class="transition hover:bg-gray-50">
                        <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4 text-gray-900">
                            <div class="font-semibold">{{ $spk->siswa?->nama ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $spk->kode_spk }}</div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $spk->siswa?->nisn ?? '-' }}</td>
                        <td class="px-5 py-4 text-gray-700">
                            <div class="font-semibold">{{ $spk->jenisPelanggaran?->nama_pelanggaran ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Skala: {{ $formatNumber($spk->skala_jenis_pelanggaran, 2) }}</div>
                        </td>
                        <td class="px-5 py-4 text-gray-700">
                            <div class="font-semibold">{{ $spk->tingkatKeterlibatan?->nama_keterangan ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Skala: {{ $formatNumber($spk->skala_tingkat_keterlibatan, 2) }}</div>
                        </td>
                        <td class="px-5 py-4 text-gray-700">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $hukumanBadgeClasses[strtolower($spk->kategori_hukuman ?? '')] ?? 'bg-zinc-100 text-zinc-700 ring-zinc-200' }}">
                                {{ $spk->nama_hukuman ?? $spk->jenisHukuman?->nama_hukuman ?? '-' }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:border-zinc-700" x-on:click="$dispatch('open-modal', 'alternatif-spk-{{ $spk->id }}')">
                                    Alternatif
                                </button>
                                <button type="button" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 hover:border-zinc-700" x-on:click="$dispatch('open-modal', 'detail-spk-{{ $spk->id }}')">
                                    Detail
                                </button>
                                <button type="button" class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 hover:border-red-400" x-on:click="$dispatch('open-modal', 'delete-spk-{{ $spk->id }}')">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-ui.datatable>
        </x-ui.section-card>
    </div>

    <x-ui.modal-box name="proses-spk" title="Form Proses SPK">
        <form action="{{ route('spk.store') }}" method="POST" class="space-y-5">
            @csrf
            @include('spk.partials.process-form', [
                'siswas' => $siswas,
                'jenisPelanggarans' => $jenisPelanggarans,
                'tingkatKeterlibatans' => $tingkatKeterlibatans,
            ])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50" x-on:click="$dispatch('close-modal', 'proses-spk')">
                    Batal
                </button>
                <x-ui.action-button type="submit">Simpan & Proses</x-ui.action-button>
            </div>
        </form>
    </x-ui.modal-box>

    @foreach ($spks as $spk)
        <x-ui.modal-box name="alternatif-spk-{{ $spk->id }}" title="Data Alternatif yang Dipakai" max-width="5xl">
            <div class="space-y-4">
                @if (! empty($spk->alternatif_snapshot))
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">NISN</th>
                                    <th class="px-4 py-3">C1</th>
                                    <th class="px-4 py-3">Skala C1</th>
                                    <th class="px-4 py-3">Norm C1</th>
                                    <th class="px-4 py-3">C2</th>
                                    <th class="px-4 py-3">Skala C2</th>
                                    <th class="px-4 py-3">Norm C2</th>
                                    <th class="px-4 py-3">Nilai Akhir</th>
                                    <th class="px-4 py-3">Hukuman</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($spk->alternatif_snapshot as $alternatif)
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $alternatif['nama'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $alternatif['nisn'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $alternatif['c1'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['skala_c1'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['normalisasi_c1'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $alternatif['c2'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['skala_c2'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['normalisasi_c2'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['nilai_akhir'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $alternatif['hukuman'] ?? 'Tidak ada' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-6 text-sm text-gray-600">
                        Belum ada data alternatif yang dipakai.
                    </div>
                @endif

                <div class="flex justify-end border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'alternatif-spk-{{ $spk->id }}')"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </x-ui.modal-box>

        <x-ui.modal-box name="detail-spk-{{ $spk->id }}" title="Detail SPK" max-width="4xl">
            <div class="space-y-5">
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500">Kode SPK</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->kode_spk }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-semibold uppercase text-gray-500">Tanggal</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->tanggal_spk?->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Nama Siswa</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->siswa?->nama ?? '-' }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">NISN</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->siswa?->nisn ?? '-' }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Jenis Pelanggaran (C1)</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->jenisPelanggaran?->nama_pelanggaran ?? '-' }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Skala C1</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->skala_jenis_pelanggaran, 2) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Norm C1</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->normalisasi_c1, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Tingkat Keterlibatan (C2)</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->tingkatKeterlibatan?->nama_keterangan ?? '-' }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Skala C2</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->skala_tingkat_keterlibatan, 2) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Norm C2</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->normalisasi_c2, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3 lg:col-span-2">
                        <p class="text-xs font-semibold uppercase text-gray-500">Nilai Akhir</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->nilai_akhir, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Hukuman</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->nama_hukuman ?? $spk->jenisHukuman?->nama_hukuman ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex justify-end border-t border-gray-100 pt-5">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50" x-on:click="$dispatch('close-modal', 'detail-spk-{{ $spk->id }}')">
                        Tutup
                    </button>
                </div>
            </div>
        </x-ui.modal-box>

        <x-ui.modal-box name="delete-spk-{{ $spk->id }}" title="Hapus SPK" max-width="md">
            <form action="{{ route('spk.destroy', $spk) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus data SPK untuk <span class="font-semibold">{{ $spk->siswa?->nama ?? '-' }}</span>?
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50" x-on:click="$dispatch('close-modal', 'delete-spk-{{ $spk->id }}')">
                        Batal
                    </button>
                    <x-ui.action-button type="submit" variant="danger">
                        Hapus
                    </x-ui.action-button>
                </div>
            </form>
        </x-ui.modal-box>
    @endforeach
@endsection
