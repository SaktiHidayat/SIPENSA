@props([
    'type' => null,
    'message' => null,
])

@php
    $flashType = $type;
    $flashMessage = $message;

    if (! $flashMessage && session('success')) {
        $flashType = 'success';
        $flashMessage = session('success');
    } elseif (! $flashMessage && session('status')) {
        $flashType = 'success';
        $flashMessage = session('status');
    } elseif (! $flashMessage && session('error')) {
        $flashType = 'error';
        $flashMessage = session('error');
    } elseif (! $flashMessage && session('info')) {
        $flashType = 'info';
        $flashMessage = session('info');
    } elseif (! $flashMessage && $errors->any()) {
        $flashType = 'error';
        $flashMessage = $errors->first();
    }

    $styles = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'error' => 'border-red-200 bg-red-50 text-red-700',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-700',
        'info' => 'border-blue-200 bg-blue-50 text-blue-700',
    ][$flashType ?? 'info'] ?? 'border-blue-200 bg-blue-50 text-blue-700';
@endphp

@if ($flashMessage)
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition
        {{ $attributes->merge(['class' => "mb-5 flex items-start justify-between gap-4 rounded-xl border px-4 py-3 text-sm font-medium shadow-sm {$styles}"]) }}
    >
        <span>{{ $flashMessage }}</span>

        <button
            type="button"
            class="shrink-0 rounded-full px-2 text-lg leading-none opacity-70 transition hover:opacity-100"
            x-on:click="show = false"
        >
            <span class="sr-only">Tutup notifikasi</span>
            &times;
        </button>
    </div>
@endif
