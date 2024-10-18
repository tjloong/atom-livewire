@php
$variant = $attributes->get('variant');
$closeable = $attributes->get('closeable', true);

$icon = $attributes->get('icon') ?? match ($variant) {
    'info' => 'info',
    'warning' => 'warning',
    'success' => 'check-circle',
    'danger', 'error' => 'close-circle',
    default => null,
};

$classes = $attributes->classes()
    ->add('relative w-full rounded-lg border py-4 px-6 flex gap-3')
    ->add(match ($variant) {
        'info' => 'bg-sky-100 border-sky-200 text-sky-600 [&>div>[data-atom-subheading]]:text-sky-600',
        'success' => 'bg-green-100 border-green-200 text-green-600 [&>div>[data-atom-subheading]]:text-green-600',
        'warning' => 'bg-yellow-100 border-yellow-200 text-yellow-600 [&>div>[data-atom-subheading]]:text-yellow-600',
        'danger', 'error' => 'bg-red-100 border-red-200 text-red-600 [&>div>[data-atom-subheading]]:text-red-600',
        default => 'border-zinc-200 bg-zinc-100',
    })
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['variant', 'closeable'])
    ;
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    {{ $attrs }}>
    @if ($icon)
        <atom:icon :name="$icon" size="24" class="shrink-0"/>
    @endif

    <div class="grow">
        {{ $slot }}
    </div>

    @if ($closeable)
        <button
            type="button"
            x-on:click.stop="show = false"
            class="absolute top-3 right-3 p-1 flex items-center justify-center">
            <atom:icon close/>
        </button>
    @endif
</div>
