@php
    $icon = $attributes->get('icon');
    $label = tr($attributes->get('label'));
    $color = $attributes->get('color', 'gray');

    $size = collect([
        'xs' => $attributes->get('xs'),
        'md' => $attributes->get('md'),
        'lg' => $attributes->get('lg'),
        'sm' => true,
    ])->filter()->keys()->first();

    $icondim = [
        'xs' => 15,
        'sm' => 18,
        'md' => 20,
        'lg' => 24,
    ][$size];
@endphp

<span style="
    background-color: {{ in_array($color, ['white', 'black']) ? $color : colors("$color.inverted") }}; 
    color: {{ in_array($color, ['white', 'black']) ? colors("$color.inverted") : colors($color) }};
    border: 1px solid {{ in_array($color, ['white', 'black']) ? $color : colors("$color.light") }};"
    {{ $attributes->class([
        'px-2 inline-block font-semibold rounded-md',   
        [
            'xs' => 'text-xs',
            'sm' => 'text-sm',
            'md' => 'text-base',
            'lg' => 'text-lg',
        ][$size],
    ]) }}>
    @if ($slot->isNotEmpty()) {{ $slot }}
    @elseif ($icon)
        <div class="-mx-2 leading-none flex items-center">
            <div class="shrink-0 flex py-1.5 px-2">
                <x-icon :name="$icon" class="m-auto"/>
            </div>

            <div class="pr-2" x-tooltip="{!! strlen($label) > 25 ? $label : null !!}">
                {!! str($label)->limit(25) !!}
            </div>
        </div>
    @else
        {!! $label !!}
    @endif
</span>