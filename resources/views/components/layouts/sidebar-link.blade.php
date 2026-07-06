@props([
    'href',
    'active' => false,
    'indent' => false,
])

@php
    $classes = $active
        ? 'border-l-4 border-white bg-zinc-800 text-white'
        : 'hover:bg-zinc-900 text-white';

    $padding = $indent ? 'px-8 py-3' : 'px-5 py-4';
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->class(["flex items-center gap-3 border-b border-zinc-700 transition {$padding} {$classes}"]) }}
>
    {{ $icon ?? '' }}
    <span x-show="sidebar">{{ $slot }}</span>
</a>
