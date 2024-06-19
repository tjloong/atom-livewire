@php
$tabs = $attributes->get('tabs', []);
$size = $attributes->size();
@endphp

<div class="w-full">
    <div
        x-data="{ value: @entangle($attributes->wire('model')) }"
        {{ $attributes->class([
            'tabs inline-flex items-center gap-1 select-none p-1 bg-gray-100',
            pick([
                'rounded *:rounded-sm *:text-sm *:py-1 *:px-3' => $size === 'sm',
                'rounded-md *:rounded *:py-1.5 *:px-4' => true,
            ]),
        ])->only('class') }}>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            @foreach ($tabs as $tab)
                <x-tab :tab="$tab"/>
            @endforeach
        @endif
    </div>
</div>