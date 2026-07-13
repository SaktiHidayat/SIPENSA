@props([
    'title' => null,
    'subtitle' => null,
])

<section {{ $attributes->merge(['class' => 'rounded-xl bg-white p-6 shadow-sm']) }}>
    @if ($title || $subtitle)
        <div class="mb-4">
            @if ($title)
                <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
            @endif

            @if ($subtitle)
                <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    {{ $slot }}
</section>
