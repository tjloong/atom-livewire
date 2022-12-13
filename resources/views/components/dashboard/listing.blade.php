@props([
    'title',
    'subtitle',
    'data',
])

<x-box class="rounded-xl">
    <x-slot:header class="flex items-center gap-2">
        <div class="grow">
            <div class="font-bold md:text-lg">{{ __($title) }}</div>
            @if ($subtitle)
                <div class="font-medium text-sm text-gray-500">{{ __($subtitle) }}</div>
            @endif
        </div>
    </x-slot:header>


    <div class="flex flex-col divide-y">
        @foreach ($data as $row)
            <div class="flex justify-between flex-wrap gap-2 py-2 px-4 hover:bg-slate-100">
                <a href="{{ data_get($row, 'href') }}" class="font-medium">
                    {{ __(data_get($row, 'label')) }}
                </a>
                {{ data_get($row, 'value') }}
            </div>
        @endforeach
    </div>
</x-box>