@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $dashboardStats = $dashboardStats ?? [];
        $recentSpks = $recentSpks ?? collect();
        $latestSpk = $latestSpk ?? null;
        $formatNumber = function ($value, int $precision = 4) {
            if ($value === null || $value === '') {
                return '0';
            }

            $formatted = number_format((float) $value, $precision, '.', '');

            return rtrim(rtrim($formatted, '0'), '.');
        };
    @endphp

    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-2xl font-bold text-gray-900">
                Selamat Datang, {{ auth()->user()->nama }}
            </h2>
            <p class="mt-2 text-sm text-gray-500">
                Ringkasan data SIPENSA dan status SPK terbaru.
            </p>
        </div>

        <div class="mx-auto grid max-w-6xl gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @if (auth()->user()?->role === 'admin')
                <a href="{{ route('spk.index') }}" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total SPK</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $dashboardStats['spks'] ?? 0 }}</p>
                </a>
            @else
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total SPK</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $dashboardStats['spks'] ?? 0 }}</p>
                </div>
            @endif
            <a href="{{ route('alternatifs.index') }}" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Alternatif</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $dashboardStats['alternatifs'] ?? 0 }}</p>
            </a>
            <a href="{{ route('siswas.index') }}" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Siswa</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ $dashboardStats['siswas'] ?? 0 }}</p>
            </a>
            @if (auth()->user()?->role === 'admin')
                <a href="{{ route('hukumans.index') }}" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-zinc-300 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Master Hukuman</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $dashboardStats['hukumans'] ?? 0 }}</p>
                </a>
            @endif
        </div>

        <div class="mx-auto grid max-w-6xl gap-6 xl:grid-cols-[1.3fr_0.7fr]">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Data SPK Terakhir</h3>
                        <p class="text-sm text-gray-500">Hasil terakhir yang sudah diproses.</p>
                    </div>
                    <a href="{{ route('spk.index') }}" class="text-sm font-semibold text-zinc-700 hover:underline">Buka SPK</a>
                </div>

                <div class="mt-4 overflow-hidden rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Nilai</th>
                                <th class="px-4 py-3">Hukuman</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($recentSpks as $spk)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">{{ $spk->siswa?->nama ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $spk->kode_spk }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $formatNumber($spk->nilai_akhir, 4) }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $spk->nama_hukuman ?? $spk->jenisHukuman?->nama_hukuman ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada data SPK.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                @if (auth()->user()?->role === 'admin')
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Master Data</h3>
                        <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                            <a href="{{ route('kriterias.index') }}" class="rounded-xl bg-gray-50 p-3 transition hover:bg-gray-100">
                                <p class="text-xs font-semibold uppercase text-gray-500">Kriteria</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ $dashboardStats['kriterias'] ?? 0 }}</p>
                            </a>
                            <a href="{{ route('pelanggarans.index') }}" class="rounded-xl bg-gray-50 p-3 transition hover:bg-gray-100">
                                <p class="text-xs font-semibold uppercase text-gray-500">Pelanggaran</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ $dashboardStats['pelanggarans'] ?? 0 }}</p>
                            </a>
                            <a href="{{ route('keterlibatans.index') }}" class="rounded-xl bg-gray-50 p-3 transition hover:bg-gray-100">
                                <p class="text-xs font-semibold uppercase text-gray-500">Keterlibatan</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ $dashboardStats['keterlibatans'] ?? 0 }}</p>
                            </a>
                            <a href="{{ route('users.index') }}" class="rounded-xl bg-gray-50 p-3 transition hover:bg-gray-100">
                                <p class="text-xs font-semibold uppercase text-gray-500">Users</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ $dashboardStats['users'] ?? 0 }}</p>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
