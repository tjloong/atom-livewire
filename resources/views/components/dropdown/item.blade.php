@props([
    'label' => $label ?? $attributes->get('label'),
    'icon' => $icon ?? $attributes->get('icon'),
])

@if ($label)
    @if ($href = $attributes->get('href'))
        <a href="{{ $href }}" {{ $attributes->merge([
            'class' => 'py-3 px-5 flex items-center gap-3 text-gray-800 font-normal hover:bg-gray-100'
        ])->except(['label', 'icon', 'href']) }}>
            @if (is_string($icon)) <x-icon :name="$icon ?? (is_string($label) ? $label : null)" class="shrink-0 text-gray-400"/>
            @elseif ($icon) {{ $icon }}
            @endif

            @if (is_string($label)) {!! __($label) !!}
            @else {{ $slot }}
            @endif
        </a>
    @else
        <div {{ $attributes->class([
            'py-3 px-5 flex items-center gap-3 text-gray-800 hover:bg-gray-100',
            $attributes->has('x-on:click') 
            || $attributes->has('x-on:click.stop') 
            || $attributes->wire('click')->value()
                ? 'cursor-pointer' 
                : null,
        ])->except('label', 'icon') }}>
            @if (is_string($icon)) <x-icon :name="$icon ?? (is_string($label) ? $label : null)" class="shrink-0 text-gray-400"/>
            @elseif ($icon) {{ $icon }}
            @endif

            @if (is_string($label)) {!! __($label) !!}
            @else {{ $slot }}
            @endif
        </div>
    @endif
@else
    <div {{ $attributes }}>
        {{ $slot }}
    </div>
@endif
