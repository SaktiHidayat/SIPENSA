@props([
    'title',
    'iconClass' => 'h-5 w-5 shrink-0 text-gray-300',
    'open' => false,
    'active' => false,
    'indent' => false,
])

@php
    $padding = $indent ? 'px-8 py-3' : 'px-5 py-4';
    $headerClasses = $active ? 'bg-zinc-900 text-white' : 'text-white hover:bg-zinc-900';
@endphp

<div x-data="{ open: @js($open) }" class="border-b border-zinc-700">
    <button
        type="button"
        class="flex w-full items-center justify-between gap-3 {{ $padding }} transition {{ $headerClasses }}"
        x-on:click="open = !open"
    >
        <span class="flex items-center gap-3">
            {{ $icon ?? '' }}
            <span x-show="sidebar">{{ $title }}</span>
        </span>

        <svg
            x-show="sidebar"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="h-4 w-4 shrink-0 transition"
            :class="open ? 'rotate-180' : ''"
        >
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>

    <div
        x-show="sidebar && open"
        x-transition
        class="bg-zinc-950/40"
    >
        {{ $slot }}
    </div>
</div>
