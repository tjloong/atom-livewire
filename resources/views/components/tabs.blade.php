@php
$tabs = $attributes->get('tabs', []);
@endphp

<div x-data="{ value: @entangle($attributes->wire('model')) }" {{ $attributes->merge([
    'class' => 'relative inline-flex flex-wrap items-center gap-1 select-none w-full p-1 bg-gray-100 rounded-md',
])->only('class') }}>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        @foreach ($tabs as $tab)
            <x-tab :tab="$tab"/>
        @endforeach
    @endif
</div>