@extends('layouts.app')

@section('title', 'Data Alternatif')

@section('content')
    @php
        $activeModal = session('modal');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">Data Alternatif</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola data alternatif siswa dengan relasi ke jenis pelanggaran dan tingkat keterlibatan.</p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2"
                x-data
                x-on:click="$dispatch('open-modal', 'create-alternatif')"
            >
                Tambah Alternatif
            </button>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_1.15fr]">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-700">
                        <span class="text-lg font-bold">!</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Gambaran Alternatif</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Alternatif berisi kode, siswa, NISN, jenis pelanggaran, dan tingkat keterlibatan. Poin tiap pilihan terisi otomatis dari tabel relasi.
                        </p>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Field</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Isi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">Kode</td>
                                <td class="px-5 py-4 text-gray-700">Diisi manual oleh admin</td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">Nama Siswa</td>
                                <td class="px-5 py-4 text-gray-700">Dipilih dari tabel siswa</td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">NISN</td>
                                <td class="px-5 py-4 text-gray-700">Terisi otomatis dari siswa yang dipilih</td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">Jenis Pelanggaran</td>
                                <td class="px-5 py-4 text-gray-700">Dipilih dari tabel jenis pelanggaran, poin dan skala fuzzy otomatis muncul</td>
                            </tr>
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">Tingkat Keterlibatan</td>
                                <td class="px-5 py-4 text-gray-700">Dipilih dari tabel tingkat keterlibatan, poin dan skala fuzzy otomatis muncul</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg bg-zinc-50 p-4 text-sm text-gray-700">
                        <p class="font-semibold text-gray-900">C1</p>
                        <p class="mt-1">Jenis pelanggaran menjadi kriteria pertama pada proses penilaian dan menghasilkan poin serta skala fuzzy.</p>
                    </div>
                    <div class="rounded-lg bg-zinc-50 p-4 text-sm text-gray-700">
                        <p class="font-semibold text-gray-900">C2</p>
                        <p class="mt-1">Tingkat keterlibatan menjadi kriteria kedua dan langsung mengikuti master datanya termasuk skala fuzzy.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Catatan Penetapan</h2>
                    <p class="mt-1 text-sm text-gray-500">Poin alternatif diambil otomatis dari relasi pilihan yang diinput oleh admin.</p>
                </div>

                <div class="space-y-4 p-6 text-sm text-gray-700">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Siswa</p>
                        <p class="mt-1">Satu alternatif mewakili satu siswa yang sedang dinilai.</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Pelanggaran</p>
                        <p class="mt-1">Jenis pelanggaran dipilih dari master data lalu poinnya muncul otomatis.</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="font-semibold text-gray-900">Keterlibatan</p>
                        <p class="mt-1">Tingkat keterlibatan juga dipilih dari master data dan diikat lewat relasi.</p>
                    </div>
                </div>
            </div>
        </div>

        <x-ui.search-bar
            :action="route('alternatifs.index')"
            :value="$search"
            placeholder="Cari kode, siswa, NISN, atau pelanggaran..."
        />

        <x-ui.datatable
            :columns="['No', 'Kode', 'Nama Siswa', 'NISN', 'Jenis Pelanggaran (C1)', 'Tingkat Keterlibatan (C2)', 'Aksi']"
            :items="$alternatifs"
            :empty-message="$search ? 'Data tidak ditemukan.' : 'Data kosong.'"
        >
            @foreach ($alternatifs as $alternatif)
                <tr class="transition hover:bg-gray-50">
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $alternatifs->firstItem() + $loop->index }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">
                        {{ $alternatif->kode_alternatif }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-700">
                        {{ $alternatif->siswa?->nama ?? '-' }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        {{ $alternatif->nisn }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-700">
                        <div class="text-sm text-gray-900">
                            <div class="font-semibold">{{ $alternatif->jenisPelanggaran?->nama_pelanggaran ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Poin: {{ $alternatif->poin_jenis_pelanggaran }}</div>
                            <div class="text-xs text-gray-500">Skala: {{ $alternatif->skala_fuzzy_jenis_pelanggaran }}</div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-700">
                        <div class="text-sm text-gray-900">
                            <div class="font-semibold">{{ $alternatif->tingkatKeterlibatan?->nama_keterangan ?? '-' }}</div>
                            <div class="text-xs text-gray-500">Skala: {{ $alternatif->skala_fuzzy_tingkat_keterlibatan }}</div>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'detail-alternatif-{{ $alternatif->id }}')"
                            >
                                Detail
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-alternatif-{{ $alternatif->id }}')"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-alternatif-{{ $alternatif->id }}')"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.datatable> 
    </div>

    <x-ui.modal-box name="create-alternatif" title="Tambah Alternatif" :show="$activeModal === 'create-alternatif'">
        <form action="{{ route('alternatifs.store') }}" method="POST" class="space-y-5">
            @csrf

            @include('alternatifs.partials.form', ['alternatif' => null, 'mode' => 'create', 'siswas' => $siswas, 'jenisPelanggarans' => $jenisPelanggarans, 'tingkatKeterlibatans' => $tingkatKeterlibatans])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    x-on:click="$dispatch('close-modal', 'create-alternatif')"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800"
                >
                    Simpan
                </button>
            </div>
        </form>
    </x-ui.modal-box>

    @foreach ($alternatifs as $alternatif)
        <x-ui.detail-modal
            name="detail-alternatif-{{ $alternatif->id }}"
            title="Detail Alternatif"
            :items="[
                'Kode Alternatif' => $alternatif->kode_alternatif,
                'Nama Siswa' => $alternatif->siswa?->nama,
                'NISN' => $alternatif->nisn,
                'Jenis Pelanggaran' => $alternatif->jenisPelanggaran?->nama_pelanggaran,
                'Poin Jenis Pelanggaran' => $alternatif->poin_jenis_pelanggaran,
                'Skala Fuzzy C1' => $alternatif->skala_fuzzy_jenis_pelanggaran,
                'Tingkat Keterlibatan' => $alternatif->tingkatKeterlibatan?->nama_keterangan,
                'Skala Fuzzy C2' => $alternatif->skala_fuzzy_tingkat_keterlibatan,
                'Dibuat' => $alternatif->created_at?->format('d M Y H:i'),
                'Diperbarui' => $alternatif->updated_at?->format('d M Y H:i'),
            ]"
        />

        <x-ui.modal-box name="edit-alternatif-{{ $alternatif->id }}" title="Edit Alternatif" :show="$activeModal === 'edit-alternatif-'.$alternatif->id">
            <form action="{{ route('alternatifs.update', $alternatif) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @include('alternatifs.partials.form', ['alternatif' => $alternatif, 'mode' => 'edit', 'siswas' => $siswas, 'jenisPelanggarans' => $jenisPelanggarans, 'tingkatKeterlibatans' => $tingkatKeterlibatans])

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'edit-alternatif-{{ $alternatif->id }}')"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800"
                    >
                        Perbarui
                    </button>
                </div>
            </form>
        </x-ui.modal-box>

        <x-ui.modal-box name="delete-alternatif-{{ $alternatif->id }}" title="Hapus Alternatif" max-width="md">
            <form action="{{ route('alternatifs.destroy', $alternatif) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus alternatif <span class="font-semibold">{{ $alternatif->kode_alternatif }}</span>?
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'delete-alternatif-{{ $alternatif->id }}')"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
                    >
                        Hapus
                    </button>
                </div>
            </form>
        </x-ui.modal-box>
    @endforeach
@endsection
