@extends('layouts.app')

@section('title', 'Data Jenis Hukuman')

@section('content')
    @php
        $activeModal = session('modal');
        $categories = \App\Models\JenisHukuman::categories();
        $levelBadgeClasses = [
            'sangat rendah' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'rendah' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'sedang' => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
            'tinggi' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'sangat tinggi' => 'bg-red-50 text-red-700 ring-red-200',
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 rounded-xl bg-white p-6 shadow-sm sm:flex-row sm:items-center">
            <div>
                <p class="text-sm font-medium uppercase tracking-wide text-gray-500">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900">Data Jenis Hukuman</h1>
                <p class="mt-2 text-sm text-gray-500">Kelola kategori hukuman dan rentang poin yang terisi otomatis dari kategori.</p>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-700 focus:ring-offset-2"
                x-data
                x-on:click="$dispatch('open-modal', 'create-jenis-hukuman')"
            >
                Tambah Jenis Hukuman
            </button>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_1.15fr]">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-zinc-100 text-zinc-700">
                        <span class="text-lg font-bold">!</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Catatan Pengisian</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Kategori dipilih dari opsi yang tersedia, lalu rentang poin terisi otomatis sesuai kategori.
                        </p>
                    </div>
                </div>

                <div class="mt-5 rounded-lg bg-gray-50 p-4 text-sm text-gray-700">
                    <p class="font-semibold text-gray-900">Kategori yang tersedia:</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($categories as $label)
                            <span class="inline-flex rounded-full bg-white px-3 py-1 text-xs font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200">
                                {{ $label }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Panduan Nilai</h2>
                    <p class="mt-1 text-sm text-gray-500">Rentang poin mengikuti kategori yang dipilih pada form.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kategori</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Rentang Poin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">Sangat Rendah</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-700">0 - 0.15</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">Rendah</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-700">0.16 - 0.3</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">Sedang</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-700">0.31 - 0.55</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">Tinggi</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-700">0.56 - 0.8</td>
                            </tr>
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-gray-900">Sangat Tinggi</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-700">0.81 - 1</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-ui.search-bar
            :action="route('hukumans.index')"
            :value="$search"
            placeholder="Cari kategori atau rentang poin..."
        />

        <x-ui.datatable
            :columns="['No', 'Kode', 'Nama Hukuman', 'Kategori', 'Rentang Poin', 'Aksi']"
            :items="$jenisHukumans"
            :empty-message="$search ? 'Data tidak ditemukan.' : 'Data kosong.'"
        >
            @foreach ($jenisHukumans as $jenisHukuman)
                <tr class="transition hover:bg-gray-50">
                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">
                        {{ $jenisHukumans->firstItem() + $loop->index }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="font-medium text-gray-900">{{ $jenisHukuman->kode_hukuman }}</span>
                    </td>
                    <td class="px-5 py-4 text-gray-700">
                        {{ $jenisHukuman->nama_hukuman }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $levelBadgeClasses[$jenisHukuman->kategori] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">
                            {{ $categories[$jenisHukuman->kategori] ?? ucfirst($jenisHukuman->kategori) }}
                        </span>
                    </td>
                    <td class="whitespace-nowrap px-5 py-4 text-gray-700">
                        {{ $jenisHukuman->rentang_poin_display }}
                    </td>
                    <td class="whitespace-nowrap px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'detail-jenis-hukuman-{{ $jenisHukuman->id }}')"
                            >
                                Detail
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-zinc-700 hover:text-zinc-900"
                                x-data
                                x-on:click="$dispatch('open-modal', 'edit-jenis-hukuman-{{ $jenisHukuman->id }}')"
                            >
                                Edit
                            </button>

                            <button
                                type="button"
                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50"
                                x-data
                                x-on:click="$dispatch('open-modal', 'delete-jenis-hukuman-{{ $jenisHukuman->id }}')"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-ui.datatable>
    </div>

    <x-ui.modal-box name="create-jenis-hukuman" title="Tambah Jenis Hukuman" :show="$activeModal === 'create-jenis-hukuman'">
        <form action="{{ route('hukumans.store') }}" method="POST" class="space-y-5">
            @csrf

            @include('hukumans.partials.form', ['jenisHukuman' => null, 'mode' => 'create'])

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                <button
                    type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    x-on:click="$dispatch('close-modal', 'create-jenis-hukuman')"
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

    @foreach ($jenisHukumans as $jenisHukuman)
        <x-ui.detail-modal
            name="detail-jenis-hukuman-{{ $jenisHukuman->id }}"
            title="Detail Jenis Hukuman"
            :items="[
                'Kode Hukuman' => $jenisHukuman->kode_hukuman,
                'Nama Hukuman' => $jenisHukuman->nama_hukuman,
                'Kategori' => $categories[$jenisHukuman->kategori] ?? ucfirst($jenisHukuman->kategori),
                'Rentang Poin' => $jenisHukuman->rentang_poin_display,
                'Dibuat' => $jenisHukuman->created_at?->format('d M Y H:i'),
                'Diperbarui' => $jenisHukuman->updated_at?->format('d M Y H:i'),
            ]"
        />

        <x-ui.modal-box name="edit-jenis-hukuman-{{ $jenisHukuman->id }}" title="Edit Jenis Hukuman" :show="$activeModal === 'edit-jenis-hukuman-'.$jenisHukuman->id">
            <form action="{{ route('hukumans.update', $jenisHukuman) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                @include('hukumans.partials.form', ['jenisHukuman' => $jenisHukuman, 'mode' => 'edit'])

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'edit-jenis-hukuman-{{ $jenisHukuman->id }}')"
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

        <x-ui.modal-box name="delete-jenis-hukuman-{{ $jenisHukuman->id }}" title="Hapus Jenis Hukuman" max-width="md">
            <form action="{{ route('hukumans.destroy', $jenisHukuman) }}" method="POST" class="space-y-5">
                @csrf
                @method('DELETE')

                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Yakin ingin menghapus jenis hukuman <span class="font-semibold">{{ $jenisHukuman->nama_hukuman }}</span>?
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                    <button
                        type="button"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                        x-on:click="$dispatch('close-modal', 'delete-jenis-hukuman-{{ $jenisHukuman->id }}')"
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
