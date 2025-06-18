@php
$size = $attributes->get('size');
$block = $attributes->has('block');
$href = $attributes->get('href');
$rel = $attributes->get('rel', 'noopener noreferrer nofollow');
$newtab = $attributes->has('newtab');
$action = $attributes->get('action');
$phrase = $attributes->get('phrase', '');
$tooltip = $attributes->get('tooltip');
$inverted = $attributes->get('inverted');
$social = $attributes->get('social');
$model = $attributes->get('model');

$variant = $attributes->get('variant') ?? get($social, 'name') ?? match ($action) {
    'submit', 'save' => 'primary',
    'delete', 'trash', 'remove' => 'danger',
    default => 'default',
};

$icon = [
    'start' => $attributes->get('icon') ?? get($social, 'name') ?? $action ?? null,
    'end' => $attributes->get('icon-suffix'),
    'size' => match ($size) {
        'lg' => 22,
        'sm' => 15,
        'xs' => 12,
        default => 18,
    },
];

$classes = $attributes->classes()
    ->add('group/button relative items-center justify-center')
    ->add($block ? 'flex w-full' : 'inline-flex')
    ->add('whitespace-nowrap rounded-md font-medium transition-colors')
    ->add('focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring active:translate-y-px')
    ->add('disabled:pointer-events-none disabled:cursor-default disabled:opacity-50')
    ->add('group-[]/buttons:-ml-[1px] group-[]/buttons:first:ml-0')
    ;

if (in_array($variant, ['subtle', 'ghost', 'link'])) {
    match ($variant) {
        'subtle' => $classes->add('bg-zinc-800/5 text-zinc-800 border border-transparent shadow-sm hover:bg-zinc-800/10'),
        'ghost' => $classes->add('bg-transparent text-zinc-500 border border-transparent hover:bg-zinc-100 hover:shadow-sm hover:text-zinc-800'),
        'link' => $classes->add('bg-tranparent text-zinc-800 border border-transparent'),
    };
}
else if ($inverted) {
    $classes->add('shadow-sm');

    match ($variant) {
        'primary' => $classes->add('bg-primary-100 text-primary border border-transparent hover:bg-primary hover:text-primary-100'),
        'accent' => $classes->add('bg-accent-100 text-accent-900 border border-transparent hover:bg-accent'),
        'warning' => $classes->add('bg-yellow-100 text-yellow-500 border border-transparent hover:bg-yellow-500 hover:text-yellow-100'),
        'danger', 'error' => $classes->add('bg-red-100 text-red-500 border border-transparent hover:bg-red-500 hover:text-red-100'),
        'facebook' => $classes->add('bg-blue-100 text-blue-600 border border-transparent hover:bg-blue-600 hover:text-blue-100'),
        'google' => $classes->add('bg-rose-100 text-rose-600 border border-transparent hover:bg-rose-600 hover:text-rose-100'),
        'linkedin' => $classes->add('bg-sky-100 text-sky-600 border border-transparent hover:bg-sky-600 hover:text-sky-100'),
        'whatsapp' => $classes->add('bg-green-100 text-green-600 border border-transparent hover:bg-green-600 hover:text-sky-100'),
        'telegram' => $classes->add('bg-sky-100 text-sky-600 border border-transparent hover:bg-sky-600 hover:text-sky-100'),
        default => $classes->add('bg-white text-zinc-800 border border-zinc-200 hover:bg-zinc-50'),
    };
}
else {
    $classes->add('shadow-sm');

    match ($variant) {
        'primary' => $classes->add('bg-primary text-primary-100 border border-transparent hover:bg-primary-600'),
        'accent' => $classes->add('bg-accent text-accent-900 border border-transparent hover:bg-accent-600'),
        'warning' => $classes->add('bg-yellow-500 text-yellow-100 border border-transparent hover:bg-yellow-600'),
        'danger', 'error' => $classes->add('bg-red-500 text-red-100 border border-transparent hover:bg-red-600'),
        'facebook' => $classes->add('bg-blue-600 text-blue-100 border border-transparent hover:bg-blue-700'),
        'google' => $classes->add('bg-rose-600 text-rose-100 border border-transparent hover:bg-rose-700'),
        'linkedin' => $classes->add('bg-sky-600 text-sky-100 border border-transparent hover:bg-sky-700'),
        'whatsapp' => $classes->add('bg-green-600 text-green-100 border border-transparent hover:bg-green-700'),
        'telegram' => $classes->add('bg-sky-600 text-sky-100 border border-transparent hover:bg-sky-700'),
        default => $classes->add('bg-white text-zinc-800 border border-zinc-200 hover:bg-zinc-50'),
    };
}

if ($slot->isEmpty() && get($icon, 'start')) {
    match ($size) {
        'lg' => $classes->add('size-12'),
        'sm' => $classes->add('size-8'),
        'xs' => $classes->add('size-6'),
        default => $classes->add('size-10'),
    };
}
else {
    match ($size) {
        'lg' => $classes->add('text-lg h-12 px-5 gap-2'),
        'sm' => $classes->add('text-sm h-8 px-3 gap-1'),
        'xs' => $classes->add('text-xs h-6 px-2 gap-1'),
        default => $classes->add('text-base h-10 px-4 gap-2'),
    };
}

if (
    $href
    || (
        in_array(get($social, 'name'), ['google', 'facebook', 'linkedin'])
        && ($href = route('socialite.redirect', ['provider' => get($social, 'name'), ...request()->query()]))
    )
    || (
        get($social, 'name') === 'whatsapp'
        && ($href = 'https://wa.me/'.get($social, 'number').'?text='.get($social, 'text'))
    )
    || (
        get($social, 'name') === 'telegram'
        && ($href = 'https://t.me/share/url?url='.get($social, 'url').'&text='.get($social, 'text'))
    )
) {
    $el = 'a';
    $merges = [
        'href' => $href,
        'rel' => $rel,
        'target' => $newtab ? '_blank' : null,
    ];
}
else {
    $el = 'button';
    $merges = ['type' => $action === 'submit' ? 'submit' : 'button'];
}

if ($attributes->has('wire:loading') || $action) {
    $merges = [
        ...$merges,
        'wire:loading.class' => 'opacity-50 pointer-events-none is-loading',
        'wire:target' => is_string($attributes->wire('loading')->value())
            ? $attributes->wire('loading')->value()
            : $action,
    ];
}

if ($tooltip) {
    $merges = [
        ...$merges,
        'x-tooltip' => js(t($tooltip)),
    ];
}
else if ($slot->isEmpty() && $action && $tooltip !== false) {
    $merges = [
        ...$merges,
        'x-tooltip' => js(t($action)),
    ];
}

if (!$attributes->hasLike('wire:click*', 'x-on:click*') && !$href && $action && $action !== 'submit') {
    $merges = [
        ...$merges,
        'x-on:click' => match ($action) {
            'delete', 'trash' => "Atom.confirm({ type: '$action', phrase: '$phrase' }).then(() => \$wire.{$action}())",
            'footprint' => "\$wire.call('footprint', { model: '".get_class($model)."', id: '".$model->id."' })",
            default => "\$wire.call('".str()->camel($action)."')",
        },
    ];
}

if ($slot->isNotEmpty()) {
    $merges = [
        ...$merges,
        'aria-label' => strip_tags($slot->toHtml()),
    ];
}

$attrs = $attributes
    ->class($classes)
    ->merge($merges)
    ->except(['variant', 'size', 'icon', 'icon-suffix', 'block', 'newtab', 'action', 'tooltip', 'inverted', 'model', 'social', 'wire:loading'])
    ;
@endphp

<{{ $el }} {{ $attrs }}>
    <div class="absolute inset-0 items-center justify-center hidden group-[.is-loading]/button:flex">
        <atom:icon name="loading" :size="get($icon, 'size')"/>
    </div>

    <div class="inline-flex items-center justify-center gap-2 group-[.is-loading]/button:opacity-0">
        @if (get($icon, 'start'))
            <atom:icon :name="get($icon, 'start')" :size="get($icon, 'size')" class="shrink-0"/>
        @endif

        {{ $slot }}

        @if (get($icon, 'end'))
            <atom:icon :name="get($icon, 'end')" :size="get($icon, 'size')" class="shrink-0 -ml-0.5"/>
        @endif
    </div>
</{{ $el }}>
