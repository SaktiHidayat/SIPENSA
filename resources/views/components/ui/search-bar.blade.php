@props([
    'action',
    'name' => 'search',
    'value' => '',
    'placeholder' => 'Cari data...',
])

<form action="{{ $action }}" method="GET" {{ $attributes->merge(['class' => 'flex w-full flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center']) }}>
    <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
            </svg>
        </span>
        <input
            type="search"
            name="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            class="w-full rounded-lg border-gray-300 pl-10 shadow-sm focus:border-zinc-700 focus:ring-zinc-700"
        >
    </div>

    <div class="flex gap-2">
        @if ($value)
            <a
                href="{{ $action }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            >
                Reset
            </a>
        @endif

        <button
            type="submit"
            class="inline-flex items-center justify-center rounded-lg bg-black px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800"
        >
            Cari
        </button>
    </div>
</form>
