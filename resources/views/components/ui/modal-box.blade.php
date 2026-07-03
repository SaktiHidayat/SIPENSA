@props([
    'name',
    'title',
    'show' => false,
    'maxWidth' => '2xl',
])

<x-modal :name="$name" :show="$show" :max-width="$maxWidth" focusable>
    <div class="border-b border-gray-100 px-6 py-4">
        <div class="flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>

            <button
                type="button"
                class="rounded-full p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                x-on:click="$dispatch('close-modal', '{{ $name }}')"
            >
                <span class="sr-only">Tutup</span>
                <span class="text-xl leading-none">&times;</span>
            </button>
        </div>
    </div>

    <div class="px-6 py-5">
        {{ $slot }}
    </div>
</x-modal>
