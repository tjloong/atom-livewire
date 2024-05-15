@php
    $icon = $attributes->get('icon');
    $active = $attributes->has('active') ? $attributes->get('active') : null;
    $inverted = $attributes->get('inverted', true);
    $lowercase = $attributes->get('lowercase', true);

    $label = is_bool($active) && !$attributes->get('label')
        ? tr($active ? 'app.label.active' : 'app.label.inactive')
        : tr($attributes->get('label'));

    $color = is_bool($active)
        ? ($active ? 'green' : 'gray')
        : $attributes->get('color', 'gray');

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
    background-color: {{ $inverted ? color($color)->inverted() : color($color) }};
    color: {{ $inverted ? color($color) : color($color)->inverted() }};
    border: 1px solid {{ color($color) }};
" {{ $attributes->class([
    'px-2 inline-block font-medium rounded-md',
    $lowercase ? 'lowercase' : null,
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
            <div class="shrink-0 flex px-2 py-1">
                <x-icon :name="$icon" class="m-auto"/>
            </div>

            <div class="pr-2" x-tooltip.raw="{!! strlen($label) > 25 ? $label : null !!}">
                {!! str($label)->limit(25) !!}
            </div>
        </div>
    @else
        <div class="grid">
            <div class="truncate">
                {!! $label !!}
            </div>
        </div>
    @endif
</span>