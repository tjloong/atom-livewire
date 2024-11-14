@php
$route = (array) $attributes->get('route');
$value = $attributes->get('value');
$badge = $attributes->get('badge');
$action = $attributes->get('action');
$href = $attributes->get('href') ?? ($route ? route(...$route) : null);

$variant = $attributes->get('variant') ?? match ($action) {
    'delete', 'trash' => 'danger',
    default => null,
};

$icon = [
    'start' => $attributes->get('icon') ?? match ($action) {
        'delete', 'trash' => 'delete',
        default => null,
    },
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
    ->add('focus:outline-none')
    ->add('disabled:pointer-events-none disabled:cursor-default')
    ->add('group-[]/panel-sidebar:my-1')
    ->add('group-[]/menu:my-1 first:group-[]/menu:mt-0 last:group-[]/menu:mb-0')
    ->add('data-[active]:bg-white data-[active]:border data-[active]:shadow-sm')
    ->add(match ($variant) {
        'danger' => 'focus:bg-red-100 hover:text-red-500 hover:bg-red-100',
        default => 'focus:bg-zinc-800/5 hover:bg-zinc-800/5',
    })
    ;

$el = $href ? 'a' : 'button';
$attrs = $attributes
    ->class($classes)
    ->merge([
        'href' => $href,
        'type' => $href ? null : 'button',
        'data-active' => $active,
        'data-atom-menu-item' => true,
        'x-on:click' => $value ? "\$dispatch('input', '$value')" : match ($action) {
            'delete' => 'Atom.confirm({ type: \'delete\' }).then(() => $wire.delete())',
            default => null,
        },
    ])
    ->except(['icon', 'route', 'can', 'value', 'active'])
    ;
@endphp

@if ($permitted)
    <{{ $el }} {{ $attrs }}>
        @if (get($icon, 'start'))
            <atom:icon :name="get($icon, 'start')" size="18" class="shrink-0 opacity-40 group-hover/collapse:hidden"/>
            <atom:icon down size="18" class="shrink-0 opacity-40 hidden group-hover/collapse:block"/>
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
            <atom:icon :name="get($icon, 'end')" size="18" class="shrink-0 opacity-40"/>
        @endif
    </{{ $el }}>
@endif