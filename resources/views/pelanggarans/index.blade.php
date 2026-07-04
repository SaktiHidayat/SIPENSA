@extends('layouts.app')

@section('title', 'Data Jenis Pelanggaran')

@section('content')
    @php
        $activeModal = session('modal');
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">Data Jenis Pelanggaran</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Kelola data pelanggaran beserta poin dan pemetaan skala fuzzy untuk kebutuhan analisis.
                </p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2"
                x-data
                x-on:click="$dispatch('open-modal', 'create-jenis-pelanggaran')"
            >
                Tambah Jenis Pelanggaran
            </button>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_1.15fr]">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <span class="text-lg font-bold">!</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Catatan Pengisian</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Kode pelanggaran diisi manual, lalu poin pelanggaran akan dipetakan otomatis ke kategori dan skala fuzzy.
                        </p>
                    </div>
                </div>

                <div class="mt-5 rounded-lg bg-gray-50 p-4 text-sm text-gray-700">
                    <p class="font-semibold text-gray-900">Alur singkat:</p>
                    <ol class="mt-2 space-y-2 list-decimal pl-5">
                        <li>Admin mengisi kode dan nama pelanggaran.</li>
                        <li>Admin memasukkan poin pelanggaran sesuai tingkat pelanggaran.</li>
                        <li>Sistem menentukan kategori dan skala fuzzy secara otomatis.</li>
                    </ol>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Panduan Skala Fuzzy</h2>
                    <p class="mt-1 text-sm text-gray-500">Tabel ini menjadi acuan konversi poin pelanggaran ke skala fuzzy.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rentang Poin</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kategori</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rumus</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Skala Fuzzy</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($fuzzyRules as $rule)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">{{ $rule['min'] }} - {{ $rule['max'] }}</td>
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
            :action="route('pelanggarans.index')"
            :value="$search"
            placeholder="Cari kode, nama, atau poin pelanggaran..."
        />

        <x-ui.datatable
            :columns="['No', 'Kode', 'Nama Pelanggaran', 'Poin', 'Skala', 'Aksi']"
            :items="$jenisPelanggarans"
            :empty-message="$search ? 'Data tidak ditemukan.' : 'Data kosong.'"
        >
            @foreach ($jenisPelanggarans as $jenisPelanggaran)
                <tr class="transition hover:bg-gray-50">
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $jenisPelanggarans->firstItem() + $loop->index }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="inline-flex rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold text-zinc-700">
                            {{ $jenisPelanggaran->kode_pelanggaran }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-semibold text-gray-900">{{ $jenisPelanggaran->nama_pelanggaran }}</div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-700">
                        {{ $jenisPelanggaran->poin_pelanggaran }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex w-fit rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ $jenisPelanggaran->kategori_fuzzy }}
                            </span>
                            <span class="text-xs text-gray-500">
                                Skala {{ rtrim(rtrim(number_format((float) $jenisPelanggaran->skala_fuzzy, 2, '.', ''), '0'), '.') }}
                            </span>
                        </div>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'detail-jenis-pelanggaran-{{ $jenisPelanggaran->id }}')"
                            >
                                Detail
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-jenis-pelanggaran-{{ $jenisPelanggaran->id }}')"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-jenis-pelanggaran-{{ $jenisPelanggaran->id }}')"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.datatable>
    </div>

    <x-ui.modal-box name="create-jenis-pelanggaran" title="Tambah Jenis Pelanggaran" :show="$activeModal === 'create-jenis-pelanggaran'">
        <form action="{{ route('pelanggarans.store') }}" method="POST" class="space-y-5">
            @csrf

            @include('pelanggarans.partials.form', ['jenisPelanggaran' => null, 'mode' => 'create'])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    x-on:click="$dispatch('close-modal', 'create-jenis-pelanggaran')"
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

    @foreach ($jenisPelanggarans as $jenisPelanggaran)
        <x-ui.detail-modal
            name="detail-jenis-pelanggaran-{{ $jenisPelanggaran->id }}"
            title="Detail Jenis Pelanggaran"
            :items="[
                'Kode Pelanggaran' => $jenisPelanggaran->kode_pelanggaran,
                'Nama Pelanggaran' => $jenisPelanggaran->nama_pelanggaran,
                'Poin Pelanggaran' => $jenisPelanggaran->poin_pelanggaran,
                'Kategori Fuzzy' => $jenisPelanggaran->kategori_fuzzy,
                'Skala Fuzzy' => rtrim(rtrim(number_format((float) $jenisPelanggaran->skala_fuzzy, 2, '.', ''), '0'), '.'),
                'Rentang' => $jenisPelanggaran->rentang_poin,
                'Rumus' => $jenisPelanggaran->rumus_fuzzy,
                'Dibuat' => $jenisPelanggaran->created_at?->format('d M Y H:i'),
                'Diperbarui' => $jenisPelanggaran->updated_at?->format('d M Y H:i'),
            ]"
        />

        <x-ui.modal-box name="edit-jenis-pelanggaran-{{ $jenisPelanggaran->id }}" title="Edit Jenis Pelanggaran" :show="$activeModal === 'edit-jenis-pelanggaran-'.$jenisPelanggaran->id">
            <form action="{{ route('pelanggarans.update', $jenisPelanggaran) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @include('pelanggarans.partials.form', ['jenisPelanggaran' => $jenisPelanggaran, 'mode' => 'edit'])

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'edit-jenis-pelanggaran-{{ $jenisPelanggaran->id }}')"
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

        <x-ui.modal-box name="delete-jenis-pelanggaran-{{ $jenisPelanggaran->id }}" title="Hapus Jenis Pelanggaran" max-width="md">
            <form action="{{ route('pelanggarans.destroy', $jenisPelanggaran) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus jenis pelanggaran <span class="font-semibold">{{ $jenisPelanggaran->nama_pelanggaran }}</span>? Data yang sudah dihapus tidak dapat dikembalikan.
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'delete-jenis-pelanggaran-{{ $jenisPelanggaran->id }}')"
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
