@php
    $jenisPelanggaran = $jenisPelanggaran ?? null;
    $mode = $mode ?? 'create';
    $formValues = $formValues ?? [];
    $rules = \App\Models\JenisPelanggaran::fuzzyRules();
@endphp

<div
    class="grid gap-4"
    x-data="{
        point: '{{ $formValues['poin_pelanggaran'] ?? ($jenisPelanggaran?->poin_pelanggaran) }}',
        rules: @js($rules),
        validatePoint() {
            const value = Number(this.point);
            const validPoint = Number.isInteger(value) && value >= 5 && value <= 300;

            this.$refs.pointInput.setCustomValidity(
                validPoint ? '' : 'Isi satu angka bulat dari 5 sampai 300.'
            );
        },
        get selectedRule() {
            if (this.point === '' || this.point === null) {
                return null;
            }

            const value = Number(this.point);

            return this.rules.find((rule) => value >= rule.min && value <= rule.max) ?? null;
        }
    }"
>
    <div>
        <label for="{{ $mode }}-kode-pelanggaran-{{ $jenisPelanggaran?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Kode Pelanggaran
        </label>
        <input
            id="{{ $mode }}-kode-pelanggaran-{{ $jenisPelanggaran?->id ?? 'new' }}"
            name="kode_pelanggaran"
            type="text"
            value="{{ $formValues['kode_pelanggaran'] ?? ($jenisPelanggaran?->kode_pelanggaran) }}"
            placeholder="Contoh: P001"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        <p class="mt-1 text-xs text-gray-500">Kode ini diisi manual sebagai identitas unik pelanggaran.</p>
        @error('kode_pelanggaran')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-nama-pelanggaran-{{ $jenisPelanggaran?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Nama Pelanggaran
        </label>
        <input
            id="{{ $mode }}-nama-pelanggaran-{{ $jenisPelanggaran?->id ?? 'new' }}"
            name="nama_pelanggaran"
            type="text"
            value="{{ $formValues['nama_pelanggaran'] ?? ($jenisPelanggaran?->nama_pelanggaran) }}"
            placeholder="Contoh: Tidak memakai atribut sekolah"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            required
        >
        @error('nama_pelanggaran')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="{{ $mode }}-poin-pelanggaran-{{ $jenisPelanggaran?->id ?? 'new' }}" class="mb-1 block text-sm font-medium text-gray-700">
            Poin Pelanggaran
        </label>
        <input
            id="{{ $mode }}-poin-pelanggaran-{{ $jenisPelanggaran?->id ?? 'new' }}"
            name="poin_pelanggaran"
            type="number"
            min="5"
            max="300"
            step="1"
            value="{{ $formValues['poin_pelanggaran'] ?? ($jenisPelanggaran?->poin_pelanggaran) }}"
            placeholder="Contoh: 12"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            x-ref="pointInput"
            x-model="point"
            x-init="validatePoint()"
            x-on:input="validatePoint()"
            required
        >
        <p class="mt-1 text-xs text-gray-500">Masukkan satu angka bulat dari 5 sampai 300.</p>
        @error('poin_pelanggaran')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
        <p class="text-sm font-semibold text-gray-800">Preview Skala Fuzzy</p>
        <div class="mt-3">
            <div class="rounded-lg bg-white p-3 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Skala</p>
                <p class="mt-1 text-sm font-semibold text-gray-900" x-text="selectedRule ? selectedRule.skala_fuzzy : '-'">
                    {{ isset($jenisPelanggaran) && $jenisPelanggaran?->exists ? rtrim(rtrim(number_format((float) $jenisPelanggaran->skala_fuzzy, 2, '.', ''), '0'), '.') : '-' }}
                </p>
            </div>
        </div>
    </div>
</div>
