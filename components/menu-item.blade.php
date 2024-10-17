@php
$route = (array) $attributes->get('route');
$value = $attributes->get('value');
$badge = $attributes->get('badge');
$href = $attributes->get('href') ?? ($route ? route(...$route) : null);

$icon = [
    'start' => $attributes->get('icon'),
    'end' => $attributes->get('icon-end'),
];

$active = $attributes->has('active') ? $attributes->get('active') : (
    ($href && url()->current() === $href)
    || ($route && atom('route')->is($route))
);

$permitted = !$attributes->has('can') || (
    $attributes->has('can')
    && user()
    && user()->can(
        ...(explode(':', $attributes->get('can')))
    )
);

$classes = $attributes->classes()
    ->add('flex items-center gap-2 w-full text-left text-zinc-800 py-2 px-3 rounded-md')
    ->add('my-1 first:mt-0 last:mb-0')
    ->add('focus:outline-none focus:bg-zinc-800/5 hover:bg-zinc-800/5')
    ->add('disabled:pointer-events-none disabled:cursor-default')
    ;

$el = $href ? 'a' : 'button';
$attrs = $attributes
    ->class($classes)
    ->merge([
        'href' => $href,
        'type' => $href ? null : 'button',
        'data-active' => $active,
        'data-atom-menu-item' => true,
        'x-on:click' => $value ? "\$dispatch('input', '$value')" : null,
    ])
    ->except(['icon', 'route', 'can', 'value', 'active'])
    ;
@endphp

@if ($permitted)
    <{{ $el }} {{ $attrs }}>
        @if (get($icon, 'start'))
            <atom:icon
                :name="get($icon, 'start')"
                size="18"
                class="shrink-0 opacity-40">
            </atom:icon>
        @endif

        <div class="grow font-medium leading-tight whitespace-nowrap truncate">
            {{ $slot }}
        </div>

        @if ($badge)
            <div class="shrink-0 w-5 h-5 rounded flex items-center justify-center text-xs text-zinc-500 bg-zinc-200">
                {{ $badge }}
            </div>
        @endif

        @if (get($icon, 'end'))
            <atom:icon
                :name="get($icon, 'end')"
                size="18"
                class="shrink-0 opacity-40">
            </atom:icon>
        @endif
    </{{ $el }}>
@endif