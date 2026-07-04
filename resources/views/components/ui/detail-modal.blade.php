@props([
    'name',
    'title',
    'items' => [],
    'show' => false,
    'maxWidth' => '2xl',
])

<x-ui.modal-box :name="$name" :title="$title" :show="$show" :max-width="$maxWidth">
    <dl class="grid gap-3 sm:grid-cols-2">
        @foreach ($items as $label => $value)
            @php
                $displayValue = ($value === null || $value === '') ? '-' : $value;
            @endphp

            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</dt>
                <dd class="mt-1 break-words text-sm font-semibold text-gray-900">{{ $displayValue }}</dd>
            </div>
        @endforeach
    </dl>

    <div class="mt-5 flex justify-end border-t border-gray-100 pt-5">
        <button
            type="button"
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
            x-on:click="$dispatch('close-modal', '{{ $name }}')"
        >
            Tutup
        </button>
    </div>
</x-ui.modal-box>
