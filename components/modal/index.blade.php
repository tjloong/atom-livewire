@php
$name = $attributes->get('name') ?? 'modal';
$locked = $attributes->get('locked', false);
$entangle = $attributes->wire('model')->value();
$submit = $attributes->submitAction();
$inset = $attributes->get('inset', false);
$visible = $attributes->get('visible', false);

if (($sess = session('__modals')) && isset($sess[$name])) {
    $visible = $sess[$name] === 'show';

    unset($sess[$name]);
    if ($sess) session()->put('__modals', $sess);
    else session()->forget('__modals');
}
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
    x-transition.duration.200
    x-on:click="backdrop($event)"
    x-on:modal-show.window="$event.detail.name === name && show()"
    x-on:modal-close.window="$event.detail.name === name && close()"
    class="relative bg-white rounded-xl shadow-lg w-full max-w-screen-md focus:outline-none {{ $attributes->get('class', 'max-w-xl') }}"
    @if ($attributes->hasLike('x-on:backdrop-click', 'wire:backdrop-click'))
    {{ $attributes->whereStartsWith('x-on:backdrop-click') }}
    {{ $attributes->whereStartsWith('wire:backdrop-click') }}
    @elseif (!$locked)
    x-on:backdrop-click="close()"
    @endif
    {{
        $attributes
        ->whereDoesntStartWith('x-on:submit')
        ->whereDoesntStartWith('wire:submit')
        ->except(['class', 'locked', 'submit'])
    }}>
    <form
        @if ($submit === true)
        {{ $attributes->whereStartsWith('x-on:submit') }}
        {{ $attributes->whereStartsWith('wire:submit') }}
        @elseif ($submit)
        wire:submit.prevent="{{ $submit }}"
        @endif
        method="dialog"
        class="{{ $inset ? '' : 'p-6' }}">
        {{ $slot }}
    </form>

    <button
        type="button"
        x-on:click="close()"
        class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100">
        <x-icon close size="18"/>
    </button>
</dialog>