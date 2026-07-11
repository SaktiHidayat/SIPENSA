@extends('layouts.app')

@section('title', 'Data Tingkat Keterlibatan')

@section('content')
    @php
        $activeModal = session('modal');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">Data Tingkat Keterlibatan</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Kelola tingkat keterlibatan pelanggaran beserta kategori dan skala fuzzy otomatis.
                </p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2"
                x-data
                x-on:click="$dispatch('open-modal', 'create-tingkat-keterlibatan')"
            >
                Tambah Tingkat Keterlibatan
            </button>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_1.15fr]">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                        <span class="text-lg font-bold">!</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Catatan Pengisian</h2>
                <p class="mt-1 text-sm text-gray-500">
                            Kode diisi manual, lalu nama keterangan dipilih dari opsi yang tersedia. Kategori dan skala fuzzy dihitung otomatis.
                </p>
                    </div>
                </div>

                <div class="mt-5 rounded-lg bg-gray-50 p-4 text-sm text-gray-700">
                    <p class="font-semibold text-gray-900">Alur singkat:</p>
                    <ol class="mt-2 space-y-2 list-decimal pl-5">
                        <li>Admin memilih nama keterangan tingkat keterlibatan.</li>
                        <li>Admin mengisi kode keterlibatan.</li>
                        <li>Sistem menentukan kategori dan skala fuzzy secara otomatis.</li>
                    </ol>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Panduan Skala Fuzzy</h2>
                    <p class="mt-1 text-sm text-gray-500">Tabel ini menjadi acuan konversi poin keterlibatan ke skala fuzzy.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Keterangan</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kategori</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rumus</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Skala Fuzzy</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($fuzzyRules as $rule)
                                <tr>
                                    <td class="px-5 py-4 font-medium text-gray-900">{{ $rule['label'] }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-700">{{ $rule['kategori'] }}</td>
                                    <td class="px-5 py-4 text-gray-600">{{ $rule['rumus'] }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                                            {{ rtrim(rtrim(number_format((float) $rule['skala_fuzzy'], 2, '.', ''), '0'), '.') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-ui.search-bar
            :action="route('keterlibatans.index')"
            :value="$search"
            placeholder="Cari kode, keterangan, poin, atau kategori..."
        />

        <x-ui.datatable
            :columns="['No', 'Kode', 'Keterangan', 'Kategori', 'Skala', 'Aksi']"
            :items="$tingkatKeterlibatans"
            :empty-message="$search ? 'Data tidak ditemukan.' : 'Data kosong.'"
        >
            @foreach ($tingkatKeterlibatans as $tingkatKeterlibatan)
                <tr class="transition hover:bg-gray-50">
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $tingkatKeterlibatans->firstItem() + $loop->index }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                            {{ $tingkatKeterlibatan->kode_tingkat_keterlibatan }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $tingkatKeterlibatan->nama_keterangan }}</div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            {{ $tingkatKeterlibatan->kategori }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-700">
                        {{ rtrim(rtrim(number_format((float) $tingkatKeterlibatan->skala_fuzzy, 2, '.', ''), '0'), '.') }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'detail-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}')"
                            >
                                Detail
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}')"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}')"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.datatable>
    </div>

    <x-ui.modal-box name="create-tingkat-keterlibatan" title="Tambah Tingkat Keterlibatan" :show="$activeModal === 'create-tingkat-keterlibatan'">
        <form action="{{ route('keterlibatans.store') }}" method="POST" class="space-y-5">
            @csrf

            @include('keterlibatans.partials.form', ['tingkatKeterlibatan' => null, 'mode' => 'create'])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    x-on:click="$dispatch('close-modal', 'create-tingkat-keterlibatan')"
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

    @foreach ($tingkatKeterlibatans as $tingkatKeterlibatan)
        <x-ui.detail-modal
            name="detail-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}"
            title="Detail Tingkat Keterlibatan"
            :items="[
                'Kode' => $tingkatKeterlibatan->kode_tingkat_keterlibatan,
                'Nama Keterangan' => $tingkatKeterlibatan->nama_keterangan,
                'Kategori' => $tingkatKeterlibatan->kategori,
                'Skala Fuzzy' => rtrim(rtrim(number_format((float) $tingkatKeterlibatan->skala_fuzzy, 2, '.', ''), '0'), '.'),
                'Dibuat' => $tingkatKeterlibatan->created_at?->format('d M Y H:i'),
                'Diperbarui' => $tingkatKeterlibatan->updated_at?->format('d M Y H:i'),
            ]"
        />

        <x-ui.modal-box name="edit-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}" title="Edit Tingkat Keterlibatan" :show="$activeModal === 'edit-tingkat-keterlibatan-'.$tingkatKeterlibatan->id">
            <form action="{{ route('keterlibatans.update', $tingkatKeterlibatan) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @include('keterlibatans.partials.form', ['tingkatKeterlibatan' => $tingkatKeterlibatan, 'mode' => 'edit'])

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'edit-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}')"
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

        <x-ui.modal-box name="delete-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}" title="Hapus Tingkat Keterlibatan" max-width="md">
            <form action="{{ route('keterlibatans.destroy', $tingkatKeterlibatan) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus tingkat keterlibatan <span class="font-semibold">{{ $tingkatKeterlibatan->nama_keterangan }}</span>? Data yang sudah dihapus tidak dapat dikembalikan.
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'delete-tingkat-keterlibatan-{{ $tingkatKeterlibatan->id }}')"
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
