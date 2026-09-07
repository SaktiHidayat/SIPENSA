@php
    $tingkatKeterlibatan = $tingkatKeterlibatan ?? null;
    $mode = $mode ?? 'create';
    $rules = \App\Models\TingkatKeterlibatan::fuzzyRules();
@endphp

<div
    class="grid gap-4"
    x-data="{
        selectedLabel: '{{ old('nama_keterangan', $tingkatKeterlibatan?->nama_keterangan) }}',
        rules: @js($rules),
        get selectedRule() {
            if (!this.selectedLabel) {
                return null;
            }

            return this.rules.find((rule) => rule.label === this.selectedLabel) ?? null;
        },
        syncPreview() {
            return this.selectedRule;
        },
        init() {
            this.syncPreview();
        }
    }"
>
    <div>
        <label for="{{ $mode }}-kode-tingkat-keterlibatan-{{ $tingkatKeterlibatan?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Kode
        </label>
        <input
            id="{{ $mode }}-kode-tingkat-keterlibatan-{{ $tingkatKeterlibatan?->id ?? 'new' }}"
            name="kode_tingkat_keterlibatan"
            type="text"
            value="{{ old('kode_tingkat_keterlibatan', $tingkatKeterlibatan?->kode_tingkat_keterlibatan) }}"
            placeholder="Contoh: TK1"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('kode_tingkat_keterlibatan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-nama-keterangan-{{ $tingkatKeterlibatan?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Nama Keterangan
        </label>
        <select
            id="{{ $mode }}-nama-keterangan-{{ $tingkatKeterlibatan?->id ?? 'new' }}"
            name="nama_keterangan"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            x-model="selectedLabel"
            x-on:change="syncPreview()"
            required
        >
            <option value="">Pilih keterangan</option>
            @foreach ($rules as $rule)
                <option value="{{ $rule['label'] }}" @selected(old('nama_keterangan', $tingkatKeterlibatan?->nama_keterangan) === $rule['label'])>
                    {{ $rule['label'] }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Pilihan ini mengikuti tabel tingkat keterlibatan yang sudah ditentukan.</p>
        @error('nama_keterangan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
        <p class="text-sm font-semibold text-gray-800">Preview Otomatis</p>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            <div class="rounded-lg bg-white p-3 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Skala Fuzzy</p>
                <p class="mt-1 text-sm font-semibold text-gray-900" x-text="selectedRule ? selectedRule.skala_fuzzy : '-'">
                    {{ isset($tingkatKeterlibatan) && $tingkatKeterlibatan?->exists ? rtrim(rtrim(number_format((float) $tingkatKeterlibatan->skala_fuzzy, 2, '.', ''), '0'), '.') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-dashed border-gray-300 bg-white p-4">
        <p class="text-sm font-semibold text-gray-800">Catatan</p>
        <p class="mt-1 text-sm text-gray-600">Skala fuzzy diambil otomatis dari pilihan keterangan.</p>
    </div>
</div>
