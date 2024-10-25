@php
$name = $attributes->get('name') ?? 'modal';
$variant = $attributes->get('variant', 'default');
$locked = $attributes->get('locked', false);
$entangle = $attributes->wire('model')->value();
$inset = $attributes->get('inset', false);
$visible = $attributes->get('visible', false);
$closeable = $attributes->get('closeable', true);

$classes = $attributes->classes()
    ->add('bg-white focus:outline-none')
    ->add(match ($variant) {
        'slide' => 'fixed shadow-lg w-full m-0 ml-auto min-h-dvh max-h-dvh',
        'full' => 'fixed m-0 min-h-dvh min-w-full',
        default => 'rounded-xl shadow-lg w-full',
    })
    ->add(in_array($variant, ['default', 'slide']) ? $attributes->get('class', 'max-w-xl') : '')
    ;

$merges = match ($variant) {
    'slide' => [
        'x-transition:enter' => 'transition ease-in-out duration-150',
        'x-transition:enter-start' => 'opacity-0 translate-x-full',
        'x-transition:enter-end' => 'opacity-100 translate-x-0',
        'x-transition:leave' => 'transition ease-in-out duration-150',
        'x-transition:leave-start' => 'opacity-100 translate-x-0',
        'x-transition:leave-end' => 'opacity-0 translate-x-full',
        'data-atom-modal-slide' => true,
    ],
    'full' => [
        'x-transition:enter' => 'transition ease-in-out duration-150',
        'x-transition:enter-start' => 'opacity-0 translate-y-full',
        'x-transition:enter-end' => 'opacity-100 translate-y-0',
        'x-transition:leave' => 'transition ease-in-out duration-150',
        'x-transition:leave-start' => 'opacity-100 translate-y-0',
        'x-transition:leave-end' => 'opacity-0 translate-y-full',
        'data-atom-modal-full' => $variant === 'full',
    ],
    default => [
        'x-transition.duration.200' => '',
        'data-atom-modal' => $variant === 'default',
    ],
};

if (!$locked) {
    $merges = [
        ...$merges,
        'x-on:backdrop-click' => 'close()',
    ];
}

$attrs = $attributes
    ->class($classes)
    ->merge($merges)
    ->except(['name', 'variant', 'locked', 'inset', 'closeable'])
    ;
@endphp

<dialog
    wire:ignore.self
    x-data="modal({
        name: @js($name),
        locked: @js($locked),
        visible: @js($visible),
        @if ($entangle)
        entangle: @entangle($entangle),
        @endif
    })"
    x-show="visible"
    x-on:click="backdrop($event)"
    x-on:modal-show.window="$event.detail.name === name && show($event.detail.data)"
    x-on:modal-close.window="$event.detail.name === name && close()"
    {{ $attrs }}>
    @if ($closeable)
        @if ($variant === 'full')
            <button type="button" x-on:click.stop="close()"
                class="absolute top-6 right-6 z-1 uppercase flex items-center gap-2 border border-zinc-200 rounded-md text-sm py-1.5 px-3 bg-white/50">
                <atom:icon close/> @t('close')
            </button>
        @else
            <div
                x-on:click="close()"
                class="absolute top-6 right-6 z-1 cursor-pointer text-zinc-500 hover:text-zinc-800">
                <atom:icon close size="20"/>
            </div>
        @endif
    @endisset

    <div class="{{ $inset ? '' : 'p-6'}}">
        {{ $slot }}
    </div>
</dialog>