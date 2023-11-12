@php
    $href = $attributes->get('href');
    $label = $attributes->get('label');
    $badge = $attributes->get('badge');
    $small = $attributes->get('small');
    $except = ['id', 'handle', 'label', 'href', 'badge', 'small'];
@endphp

<div class="{{ $attributes->get('class', 'flex items-center') }}" data-sortable-id="{{ $attributes->get('id') }}">
    @if ($hasHandle = $attributes->get('handle'))
        @isset($handle) {{ $handle }}
        @else 
            <div class="handle shrink-0 cursor-move self-stretch">
                <div class="py-2 px-3 flex h-full">
                    <x-icon name="sort" class="m-auto text-gray-400 text-xs"/>
                </div>
            </div>
        @endisset
    @endif

    @if ($label)
        <div class="grow flex flex-col">
            <div class="flex items-center gap-3">
                @if ($href) <x-link :label="$label" :href="$href" {{ $attributes->except($except) }}/>
                @else <div class="font-medium" {{ $attributes->except($exept) }}>{{ tr($label) }}</div>
                @endif

                @if ($badge && (is_string($badge) || is_numeric($badge))) <x-badge :label="$badge" color="blue"/>
                @elseif ($badge) <x-badge :label="data_get($badge, 'label')" :color="data_get($badge, 'color')"/>
                @endif
            </div>

            @if ($small)
                <div class="text-gray-500">{{ tr($small) }}</div>
            @endif
        </div>
    @else
        <div class="grow">
            {{ $slot }}
        </div>
    @endif
</div>