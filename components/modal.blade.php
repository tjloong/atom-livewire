@php
$name = $attributes->get('name') ?? 'modal';
$variant = $attributes->get('variant', 'default');
$locked = $attributes->get('locked', false);
$entangle = $attributes->wire('model')->value();
$inset = $attributes->get('inset', false);
$visible = $attributes->get('visible', false);
$closeable = $attributes->get('closeable', true);

$attrs = $attributes->except(['name', 'variant', 'locked', 'inset', 'closeable', 'class']);
@endphp

<dialog
wire:ignore.self
x-data="modal({
    name: @js($name),
    locked: @js($locked),
    visible: @js($visible),
    variant: @js($variant),
    @if ($entangle)
    entangle: @entangle($entangle),
    @endif
})"
x-on:modal-show.window="$event.detail.name === name && show($event.detail.data, $event.detail.variant)"
x-on:modal-close.window="$event.detail.name === name && close()"
x-on:keydown.esc.prevent="close()"
data-atom-modal
class="relative min-w-full min-h-dvh m-0 bg-transparent overflow-hidden focus:outline-none"
{{ $attrs }}>
    <div
    wire:ignore
    x-ref="backdrop"
    x-on:click="!locked && close()"
    class="absolute z-1 inset-0 bg-black/20 opacity-0 transition-opacity duration-100"></div>

    <div
    wire:ignore.self
    x-ref="modal"
    x-bind:style="variant === 'full' ? { width: '100vw', maxWidth: '100vw' } : null"
    class="absolute z-1 bg-white shadow-lg w-full overflow-hidden opacity-0 transition ease-in-out duration-150 {{ $attributes->get('class', 'max-w-xl') }}">
        @if ($closeable)
            <button
            type="button"
            x-on:click.stop="close()"
            x-bind:class="variant === 'full'
                ? 'top-6 right-6 py-1.5 px-3 border border-zinc-200 bg-white/50 hover:bg-zinc-100'
                : 'top-5 right-5 w-8 h-8 hover:bg-zinc-100'"
            class="absolute z-1 uppercase flex items-center justify-center gap-2 rounded-md text-sm">
                <atom:icon close/>
                <div x-show="variant === 'full'">@t('close')</div>
            </button>
        @endisset

        <div
        x-bind:style="{ maxHeight: variant === 'slide' ? '100vh' : '95vh' }"
        class="overflow-auto {{ $inset ? '' : 'p-6' }}">
            @if ($slot->isEmpty()) <atom:skeleton/>
            @else {{ $slot }}
            @endif
        </div>
    </div>
</dialog>
