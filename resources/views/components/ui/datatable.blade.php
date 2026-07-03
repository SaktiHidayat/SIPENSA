@props([
    'columns' => [],
    'items' => null,
    'emptyMessage' => 'Data kosong.',
])

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 text-sm']) }}>
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($columns as $column)
                        <th scope="col" class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            {{ $column }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
                @if ($items && $items->count())
                    {{ $slot }}
                @else
                    <tr>
                        <td colspan="{{ max(count($columns), 1) }}" class="px-5 py-12 text-center">
                            <div class="mx-auto flex max-w-sm flex-col items-center">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                    <span class="text-xl">!</span>
                                </div>
                                <p class="font-semibold text-gray-700">{{ $emptyMessage }}</p>
                                <p class="mt-1 text-sm text-gray-500">Belum ada data yang bisa ditampilkan.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($items && $items->hasPages())
        <div class="border-t border-gray-100 bg-gray-50 px-5 py-4">
            {{ $items->links() }}
        </div>
    @endif
</div>
