@php
    $siswas = $siswas ?? collect();
    $jenisPelanggarans = $jenisPelanggarans ?? collect();
    $tingkatKeterlibatans = $tingkatKeterlibatans ?? collect();
@endphp

<div
    class="grid gap-4"
    x-data="{
        siswas: @js($siswas->map(fn ($siswa) => ['id' => $siswa->id, 'nama' => $siswa->nama, 'nisn' => $siswa->nisn])->values()),
        pelanggarans: @js($jenisPelanggarans->map(fn ($item) => ['id' => $item->id, 'label' => $item->kode_pelanggaran.' - '.$item->nama_pelanggaran, 'skala_fuzzy' => $item->skala_fuzzy])->values()),
        keterlibatans: @js($tingkatKeterlibatans->map(fn ($item) => ['id' => $item->id, 'label' => $item->kode_tingkat_keterlibatan.' - '.$item->nama_keterangan, 'skala_fuzzy' => $item->skala_fuzzy])->values()),
        siswaId: @js(old('siswa_id', request('siswa_id', ''))),
        jenisPelanggaranId: @js(old('jenis_pelanggaran_id', request('jenis_pelanggaran_id', ''))),
        tingkatKeterlibatanId: @js(old('tingkat_keterlibatan_id', request('tingkat_keterlibatan_id', ''))),
        skalaFuzzyPelanggaran: '',
        skalaFuzzyKeterlibatan: '',
        displayValue(value) {
            return value === '' || value === null || value === undefined ? '-' : value;
        },
        syncSiswa() {
            return this.siswas.find(item => String(item.id) === String(this.siswaId));
        },
        syncPelanggaran() {
            const item = this.pelanggarans.find(item => String(item.id) === String(this.jenisPelanggaranId));
            this.skalaFuzzyPelanggaran = item ? item.skala_fuzzy : '';
        },
        syncKeterlibatan() {
            const item = this.keterlibatans.find(item => String(item.id) === String(this.tingkatKeterlibatanId));
            this.skalaFuzzyKeterlibatan = item ? item.skala_fuzzy : '';
        },
        resetForm() {
            this.siswaId = '';
            this.jenisPelanggaranId = '';
            this.tingkatKeterlibatanId = '';
            this.skalaFuzzyPelanggaran = '';
            this.skalaFuzzyKeterlibatan = '';
            this.$refs.siswa.value = '';
            this.$refs.siswa.selectedIndex = 0;
            this.$refs.pelanggaran.value = '';
            this.$refs.pelanggaran.selectedIndex = 0;
            this.$refs.keterlibatan.value = '';
            this.$refs.keterlibatan.selectedIndex = 0;
        },
        init() {
            this.siswaId = this.$refs.siswa.value;
            this.jenisPelanggaranId = this.$refs.pelanggaran.value;
            this.tingkatKeterlibatanId = this.$refs.keterlibatan.value;
            this.syncSiswa();
            this.syncPelanggaran();
            this.syncKeterlibatan();
        }
    }"
    x-init="init()"
    x-on:reset-spk-form.window="resetForm()"
>
    <div>
        <label for="spk-siswa" class="mb-1 block text-sm font-medium text-gray-700">Siswa</label>
        <select
            id="spk-siswa"
            x-ref="siswa"
            name="siswa_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            x-model="siswaId"
            x-on:change="syncSiswa()"
        >
            <option value="">Pilih siswa</option>
            @foreach ($siswas as $siswa)
                <option value="{{ $siswa->id }}">{{ $siswa->nama }} - {{ $siswa->nisn }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="spk-pelanggaran" class="mb-1 block text-sm font-medium text-gray-700">Jenis Pelanggaran</label>
        <select
            x-ref="pelanggaran"
            id="spk-pelanggaran"
            name="jenis_pelanggaran_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            x-model="jenisPelanggaranId"
            x-on:change="syncPelanggaran()"
        >
            <option value="">Pilih jenis pelanggaran</option>
            @foreach ($jenisPelanggarans as $pelanggaran)
                <option value="{{ $pelanggaran->id }}">{{ $pelanggaran->kode_pelanggaran }} - {{ $pelanggaran->nama_pelanggaran }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="spk-keterlibatan" class="mb-1 block text-sm font-medium text-gray-700">Tingkat Keterlibatan</label>
        <select
            x-ref="keterlibatan"
            id="spk-keterlibatan"
            name="tingkat_keterlibatan_id"
            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
            x-model="tingkatKeterlibatanId"
            x-on:change="syncKeterlibatan()"
        >
            <option value="">Pilih tingkat keterlibatan</option>
            @foreach ($tingkatKeterlibatans as $keterlibatan)
                <option value="{{ $keterlibatan->id }}">{{ $keterlibatan->kode_tingkat_keterlibatan }} - {{ $keterlibatan->nama_keterangan }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid gap-3 sm:grid-cols-2">
        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Skala Fuzzy C1</p>
            <p class="mt-1 text-lg font-semibold text-gray-900" x-text="displayValue(skalaFuzzyPelanggaran)">-</p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Skala Fuzzy C2</p>
            <p class="mt-1 text-lg font-semibold text-gray-900" x-text="displayValue(skalaFuzzyKeterlibatan)">-</p>
        </div>
    </div>

    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
        <p class="text-sm font-semibold text-gray-800">Catatan Proses</p>
        <div class="mt-2 space-y-1 text-sm text-gray-600">
            <p>C1: jenis pelanggaran, C2: tingkat keterlibatan.</p>
            <p>Skala fuzzy diambil langsung dari master data yang dipilih.</p>
        </div>
    </div>
</div>
