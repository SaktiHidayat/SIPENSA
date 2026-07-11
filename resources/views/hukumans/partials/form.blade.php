@php
    $jenisHukuman = $jenisHukuman ?? null;
    $mode = $mode ?? 'create';
    $categories = \App\Models\JenisHukuman::categories();
    $ranges = \App\Models\JenisHukuman::ranges();
    $selectedCategory = old('kategori', $jenisHukuman?->kategori);
@endphp

<div
    class="grid gap-4"
    x-data="{
        kategori: @js($selectedCategory),
        ranges: @js($ranges),
        rentang: @js(\App\Models\JenisHukuman::rangeForCategory($selectedCategory)),
        syncRange() {
            this.rentang = this.ranges[this.kategori] ?? '';
        }
    }"
    x-init="syncRange()"
>
    <div>
        <label for="{{ $mode }}-kode-hukuman-{{ $jenisHukuman?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Kode Hukuman
        </label>
        <input
            id="{{ $mode }}-kode-hukuman-{{ $jenisHukuman?->id ?? 'new' }}"
            type="text"
            name="kode_hukuman"
            value="{{ old('kode_hukuman', $jenisHukuman?->kode_hukuman) }}"
            placeholder="Contoh: HK001"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('kode_hukuman')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-nama-hukuman-{{ $jenisHukuman?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Nama Hukuman
        </label>
        <input
            id="{{ $mode }}-nama-hukuman-{{ $jenisHukuman?->id ?? 'new' }}"
            type="text"
            name="nama_hukuman"
            value="{{ old('nama_hukuman', $jenisHukuman?->nama_hukuman) }}"
            placeholder="Contoh: Teguran"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('nama_hukuman')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-kategori-{{ $jenisHukuman?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Kategori
        </label>
        <select
            id="{{ $mode }}-kategori-{{ $jenisHukuman?->id ?? 'new' }}"
            name="kategori"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
            x-model="kategori"
            @change="syncRange()"
        >
            <option value="">Pilih kategori</option>
            @foreach ($categories as $value => $label)
                <option value="{{ $value }}" @selected(old('kategori', $jenisHukuman?->kategori) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('kategori')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-rentang-poin-{{ $jenisHukuman?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Rentang Poin
        </label>
        <input
            id="{{ $mode }}-rentang-poin-{{ $jenisHukuman?->id ?? 'new' }}"
            type="text"
            :value="rentang"
            placeholder="Pilih kategori terlebih dahulu"
            class="w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            readonly
        >
        <p class="mt-1 text-xs text-gray-500">Rentang poin otomatis mengikuti kategori yang dipilih.</p>
    </div>
</div>
