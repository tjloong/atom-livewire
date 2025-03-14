@php
$icon = $attributes->get('icon');
$active = $attributes->has('active') ? $attributes->get('active') : null;
$inverted = $attributes->get('invert') ?? $attributes->get('inverted') ?? true;
$lowercase = $attributes->get('lower') ?? $attributes->get('lowercase') ?? true;
$size = $attributes->size('sm');
$badge = $attributes->get('badge');

$label = $attributes->get('label')
    ?? (is_string($badge) ? $badge : get($badge, 'label'))
    ?? (is_bool($active) ? ($active ? 'app.label.active' : 'app.label.inactive') : null);

$label = is_array($label) ? tr(...$label) : tr($label);

$color = $attributes->get('color')
    ?? get($badge, 'color')
    ?? (is_bool($active) ? ($active ? 'green' : 'gray') : 'gray');

$except = ['label', 'icon', 'active', 'invert', 'inverted', 'lower', 'lowercase', 'badge'];
@endphp

<span
    style="
        background-color: {{ $inverted ? color($color)->inverted() : color($color) }};
        color: {{ $inverted ? color($color) : color($color)->inverted() }};
        border: 1px solid {{ color($color) }};
    "
    {{ $attributes->class([
        'px-2 inline-block font-medium rounded-md',
        $lowercase ? 'lowercase' : null,
        'text-'.($size == 'md' ? 'base' : $size),
    ])->except($except) }}>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @elseif ($icon && $label)
        <div class="-mx-2 leading-none flex items-center">
            <div class="shrink-0 flex px-2 py-1">
                <x-icon :name="$icon" class="m-auto"/>
            </div>

            <div class="pr-2" x-tooltip="{!! js(strlen($label) > 25 ? $label : null) !!}">
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