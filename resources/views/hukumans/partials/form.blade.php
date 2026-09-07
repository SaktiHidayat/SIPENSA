@php
    $jenisHukuman = $jenisHukuman ?? null;
    $mode = $mode ?? 'create';
    $storedRange = old('rentang_poin', $jenisHukuman?->rentang_poin);
    preg_match('/^\s*([0-9]+(?:\.[0-9]+)?)\s*-\s*([0-9]+(?:\.[0-9]+)?)/', (string) $storedRange, $rangeMatches);
    $rangeFirst = old('rentang_poin_awal', $rangeMatches[1] ?? '');
    $rangeSecond = old('rentang_poin_akhir', $rangeMatches[2] ?? '');
@endphp

<div
    class="grid gap-4"
    x-data="{
        first: @js($rangeFirst),
        second: @js($rangeSecond),
        get combinedRange() {
            return `${this.first} - ${this.second}`;
        }
    }"
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
        <p class="mb-1 block text-sm font-medium text-gray-700">Rentang Poin</p>
        <div class="grid gap-3 sm:grid-cols-2">
            <div>
                <label for="{{ $mode }}-rentang-poin-awal-{{ $jenisHukuman?->id ?? 'new' }}" class="mb-1 block text-xs font-medium text-gray-600">
                    Rentang Pertama
                </label>
                <input
                    id="{{ $mode }}-rentang-poin-awal-{{ $jenisHukuman?->id ?? 'new' }}"
                    type="number"
                    name="rentang_poin_awal"
                    inputmode="decimal"
                    min="0.1"
                    step="0.01"
                    x-model="first"
                    placeholder="Contoh: 0.10"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
                    required
                >
                @error('rentang_poin_awal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="{{ $mode }}-rentang-poin-akhir-{{ $jenisHukuman?->id ?? 'new' }}" class="mb-1 block text-xs font-medium text-gray-600">
                    Rentang Kedua
                </label>
                <input
                    id="{{ $mode }}-rentang-poin-akhir-{{ $jenisHukuman?->id ?? 'new' }}"
                    type="number"
                    name="rentang_poin_akhir"
                    inputmode="decimal"
                    min="0.1"
                    step="0.01"
                    x-model="second"
                    placeholder="Contoh: 0.30"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
                    required
                >
                @error('rentang_poin_akhir')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <input type="hidden" name="rentang_poin" x-bind:value="combinedRange">
        <p class="mt-1 text-xs text-gray-500">Kedua nilai akan digabung otomatis dengan tanda "-".</p>
    </div>
</div>
