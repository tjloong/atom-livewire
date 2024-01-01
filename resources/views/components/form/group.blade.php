@php
    $cols = $attributes->get('cols');
@endphp

@if ($slot->isNotEmpty())
    <div class="flex flex-col gap-2 {{ $attributes->get('class', 'p-5 border-t first:border-t-0') }}" {{ $attributes->except('cols') }}>
        @isset($heading)
            {{ $heading }}
        @elseif ($heading = $attributes->get('heading'))
            <x-heading :title="$heading" class="mb-3" sm>
                @isset($buttons) {{ $buttons }} @endisset
            </x-heading>
        @endif

        <div class="grid gap-5 grid-cols-1 {{
            [
                2 => 'md:grid-cols-2',
                3 => 'md:grid-cols-3',
                4 => 'md:grid-cols-4',
                5 => 'md:grid-cols-5',
                6 => 'md:grid-cols-6',
            ][$cols] ?? ''
        }}">
            {{ $slot }}
        </div>
    </div>
@endif