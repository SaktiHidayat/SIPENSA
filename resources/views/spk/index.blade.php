@extends('layouts.app')

@section('title', 'Data SPK')

@section('content')
    @php
        $spks = $spks ?? collect();
        $latestSpk = $spks->first();
        $formatNumber = function ($value, int $precision = 4) {
            if ($value === null || $value === '') {
                return '0';
            }

            $formatted = number_format((float) $value, $precision, '.', '');

            return rtrim(rtrim($formatted, '0'), '.');
        };
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
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Nilai Akhir</p>
                                <p class="mt-2 text-lg font-semibold text-gray-900">{{ $formatNumber($latestSpk->total_nilai_akhir, 4) }}</p>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Hukuman</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
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
                    Tambah Siswa
                </x-ui.action-button>
            </div>
        </x-ui.section-card>

        <x-ui.section-card title="Siswa yang Akan Diproses" subtitle="Tambahkan minimal 5 siswa, lalu jalankan proses SPK.">
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Kelas</th>
                            <th class="px-4 py-3">C1</th>
                            <th class="px-4 py-3">C2</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($candidates as $candidate)
                            <tr>
                                <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $candidate->nama }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $candidate->kelas }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $candidate->jenisPelanggaran?->nama_pelanggaran ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $candidate->tingkatKeterlibatan?->nama_keterangan ?? '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-3">
                                    <button type="button" class="mr-2 text-xs font-semibold text-zinc-700 hover:underline" x-data x-on:click="$dispatch('open-modal', 'edit-calon-{{ $candidate->id }}')">Edit</button>
                                    <form class="inline" action="{{ route('spk.candidates.destroy', $candidate) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-700 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada siswa yang akan diproses.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                <p class="text-sm text-gray-600">{{ $candidates->count() }} siswa tersimpan. Minimal 5 siswa untuk proses.</p>
                <form action="{{ route('spk.process') }}" method="POST">
                    @csrf
                    <x-ui.action-button type="submit" :disabled="$candidates->count() < 5">Proses SPK</x-ui.action-button>
                </form>
            </div>
        </x-ui.section-card>

        <div class="grid gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm lg:grid-cols-[1fr_1.4fr_auto] lg:items-end">
        <form action="{{ route('spk.index') }}" method="GET" class="contents">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700" for="search">Nama atau NISN</label>
                <input id="search" type="search" name="search" value="{{ $searchStudent }}" placeholder="Cari nama siswa atau NISN..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700">
            </div>
            <div x-data="{ tanggalMulai: @js($searchTanggalMulai) }" class="grid gap-2 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="tanggal_mulai">Tanggal Mulai</label>
                    <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ $searchTanggalMulai }}" x-model="tanggalMulai" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="tanggal_akhir">Tanggal Akhir</label>
                    <input id="tanggal_akhir" type="date" name="tanggal_akhir" value="{{ $searchTanggalAkhir }}" x-bind:min="tanggalMulai || null" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700">
                </div>
            </div>
            <div class="flex gap-2">
                <x-ui.action-button type="submit">Cari</x-ui.action-button>
                <a href="{{ route('spk.index') }}">
                    <x-ui.action-button variant="secondary" type="button">Reset</x-ui.action-button>
                </a>
            </div>
        </form>
        </div>

        <x-ui.section-card title="Hasil SPK" subtitle="Nilai akhir dihitung dari kontribusi C1 dan C2 dengan bobot awal 0.75 dan 0.25.">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm text-gray-500">
                    Cetak PDF mengikuti tanggal yang sedang dicari. Kalau tanggal kosong, semua data akan dicetak.
                </p>
                <div class="flex items-center gap-2">
                    <x-ui.print-pdf-button :href="route('spk.pdf', request()->only(['tanggal_mulai', 'tanggal_akhir', 'search']))" />
                    <form action="{{ route('spk.reset-display') }}" method="POST">
                        @csrf
                        <x-ui.action-button variant="secondary" type="submit">Reset SPK</x-ui.action-button>
                    </form>
                </div>
            </div>

            <x-ui.datatable
                :columns="['No', 'Nama', 'NISN', 'C1', 'C2', 'Total Nilai Akhir', 'Hukuman', 'Aksi']"
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
                            <div class="font-semibold">{{ $formatNumber($spk->total_nilai_akhir, 4) }}</div>
                            <div class="text-xs text-gray-500">Total nilai akhir</div>
                        </td>
                        <td class="px-5 py-4 text-gray-700">
                            <span class="inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
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
        <form action="{{ route('spk.candidates.store') }}" method="POST" class="space-y-5">
            @csrf
            @include('spk.partials.process-form', [
                'jenisPelanggarans' => $jenisPelanggarans,
                'tingkatKeterlibatans' => $tingkatKeterlibatans,
                'pointTotals' => $pointTotals,
            ])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button type="button" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50" x-on:click="$dispatch('close-modal', 'proses-spk')">
                    Batal
                </button>
                <x-ui.action-button type="submit">Simpan Siswa</x-ui.action-button>
            </div>
        </form>
    </x-ui.modal-box>

    @foreach ($candidates as $candidate)
        <x-ui.modal-box name="edit-calon-{{ $candidate->id }}" title="Edit Calon Siswa">
            <form action="{{ route('spk.candidates.update', $candidate) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="mb-1 block text-sm font-medium text-gray-700">Nama Siswa</label><input name="nama" value="{{ $candidate->nama }}" class="w-full rounded-lg border-gray-300 shadow-sm" required></div>
                    <div><label class="mb-1 block text-sm font-medium text-gray-700">Kelas</label><input name="kelas" value="{{ $candidate->kelas }}" class="w-full rounded-lg border-gray-300 shadow-sm" required></div>
                </div>
                <div><label class="mb-1 block text-sm font-medium text-gray-700">Jenis Pelanggaran</label><select name="jenis_pelanggaran_id" class="w-full rounded-lg border-gray-300 shadow-sm" required>@foreach ($jenisPelanggarans as $item)<option value="{{ $item->id }}" @selected($item->id === $candidate->jenis_pelanggaran_id)>{{ $item->kode_pelanggaran }} - {{ $item->nama_pelanggaran }}</option>@endforeach</select></div>
                <div><label class="mb-1 block text-sm font-medium text-gray-700">Tingkat Keterlibatan</label><select name="tingkat_keterlibatan_id" class="w-full rounded-lg border-gray-300 shadow-sm" required>@foreach ($tingkatKeterlibatans as $item)<option value="{{ $item->id }}" @selected($item->id === $candidate->tingkat_keterlibatan_id)>{{ $item->kode_tingkat_keterlibatan }} - {{ $item->nama_keterangan }}</option>@endforeach</select></div>
                <div class="flex justify-end"><x-ui.action-button type="submit">Simpan Perubahan</x-ui.action-button></div>
            </form>
        </x-ui.modal-box>
    @endforeach

    @foreach ($spks as $spk)
        <x-ui.modal-box name="alternatif-spk-{{ $spk->id }}" title="Data Alternatif yang Dipakai" max-width="5xl">
            <div class="space-y-4">
                @if (! empty($spk->alternatif_snapshot))
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3">NISN</th>
                                    <th class="px-4 py-3">C1</th>
                                    <th class="px-4 py-3">Poin C1</th>
                                    <th class="px-4 py-3">Skala C1</th>
                                    <th class="px-4 py-3">Norm C1</th>
                                    <th class="px-4 py-3">Bobot Awal C1</th>
                                    <th class="px-4 py-3">Kontribusi C1</th>
                                    <th class="px-4 py-3">C2</th>
                                    <th class="px-4 py-3">Skala C2</th>
                                    <th class="px-4 py-3">Norm C2</th>
                                    <th class="px-4 py-3">Bobot Awal C2</th>
                                    <th class="px-4 py-3">Kontribusi C2</th>
                                    <th class="px-4 py-3">Poin Akhir</th>
                                    <th class="px-4 py-3">Hukuman</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($spk->alternatif_snapshot as $alternatif)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $alternatif['nama'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $alternatif['nisn'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $alternatif['c1'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['poin_c1'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['skala_c1'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['normalisasi_c1'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['bobot_c1'] ?? 0.75, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['kontribusi_c1'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $alternatif['c2'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['skala_c2'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['normalisasi_c2'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['bobot_c2'] ?? 0.25, 2) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['kontribusi_c2'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $formatNumber($alternatif['nilai_akhir'] ?? 0, 4) }}</td>
                                        <td class="px-4 py-3 text-gray-700">
                                            <div>{{ $alternatif['hukuman'] ?? 'Belum ada yang cocok' }}</div>
                                            @if (! empty($alternatif['rentang_poin_hukuman']))
                                                <div class="text-xs text-gray-500">{{ $alternatif['rentang_poin_hukuman'] }}</div>
                                            @endif
                                        </td>
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
                    <div class="border-b border-gray-200 pb-2 pt-2 sm:col-span-2 lg:col-span-3">
                        <p class="text-sm font-bold uppercase tracking-wide text-gray-900">C1 - Jenis Pelanggaran</p>
                        <p class="mt-1 text-xs text-gray-500">Bobot Awal C1: 0.75</p>
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
                        <p class="text-xs font-semibold uppercase text-gray-500">Poin C1</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->poin_jenis_pelanggaran }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Bobot Awal C1</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">0.75</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Norm C1</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->normalisasi_c1, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Kontribusi C1</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber((float) $spk->normalisasi_c1 * 0.75, 4) }}</p>
                    </div>
                    <div class="border-b border-gray-200 pb-2 pt-4 sm:col-span-2 lg:col-span-3">
                        <p class="text-sm font-bold uppercase tracking-wide text-gray-900">C2 - Tingkat Keterlibatan</p>
                        <p class="mt-1 text-xs text-gray-500">Bobot Awal C2: 0.25</p>
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
                        <p class="text-xs font-semibold uppercase text-gray-500">Bobot Awal C2</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">0.25</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Norm C2</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->normalisasi_c2, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Kontribusi C2</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber((float) $spk->normalisasi_c2 * 0.25, 4) }}</p>
                    </div>
                    <div class="border-b border-gray-200 pb-2 pt-4 sm:col-span-2 lg:col-span-3">
                        <p class="text-sm font-bold uppercase tracking-wide text-gray-900">Ringkasan Hasil</p>
                        <p class="mt-1 text-xs text-gray-500">Nilai akhir sebelumnya, sekarang, dan total.</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Nilai Akhir Sekarang</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->nilai_akhir_sekarang, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Nilai Akhir Sebelumnya</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->nilai_akhir_sebelumnya, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Total Nilai Akhir</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $formatNumber($spk->total_nilai_akhir, 4) }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Hukuman</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->nama_hukuman ?? $spk->jenisHukuman?->nama_hukuman ?? '-' }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-3">
                        <p class="text-xs font-semibold uppercase text-gray-500">Rentang Poin Hukuman</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $spk->rentang_poin_hukuman ?? $spk->jenisHukuman?->rentang_poin ?? '-' }}</p>
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
