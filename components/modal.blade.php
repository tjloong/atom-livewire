@php
$name = $attributes->get('name') ?? 'modal';
$type = $attributes->get('type', 'default');
$locked = $attributes->get('locked', false);
$entangle = $attributes->wire('model')->value();
$submit = $attributes->submitAction();
$inset = $attributes->get('inset', false);
$closeable = $attributes->get('closeable', true);
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
    x-on:click="backdrop($event)"
    x-on:modal-show.window="$event.detail.name === name && show()"
    x-on:modal-close.window="$event.detail.name === name && close()"

    @if ($type === 'default')
    x-transition.duration.200
    @elseif ($type === 'slide')
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 translate-x-full"
    x-transition:enter-end="opacity-100 translate-x-0"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-full"
    @elseif ($type === 'full')
    x-transition:enter="transition ease-in-out duration-150"
    x-transition:enter-start="opacity-0 translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in-out duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-full"
    @endif

    class="{{ collect([
        'bg-white focus:outline-none',
        $type === 'default' ? 'rounded-xl shadow-lg w-full' : null,
        $type === 'slide' ? 'fixed shadow-lg w-full m-0 ml-auto min-h-dvh max-h-dvh' : null,
        $type === 'full' ? 'fixed m-0 min-h-dvh min-w-full' : null,
        in_array($type, ['default', 'slide']) ? $attributes->get('class', 'max-w-xl') : null,
    ])->filter()->join(' ') }}"
    
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
        ->merge([
            'data-atom-modal' => $type === 'default',
            'data-atom-modal-slide' => $type === 'slide',
            'data-atom-modal-full' => $type === 'full',
        ])
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
        class="relative mx-auto {{ $inset ? '' : 'p-6' }} {{ $type === 'full' ? $attributes->get('class', 'max-w-screen-sm') : '' }}">
        {{ $slot }}

        @if ($closeable)
            <div class="absolute top-4 right-4">
                @if (in_array($type, ['default', 'slide']))
                    <button
                        type="button"
                        x-on:click="close()"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100">
                        <x-icon close size="20"/>
                    </button>
                @elseif ($type === 'full')
                    <x-button icon="close" label="app.label.close" x-on:click="close()"/>
                @endif
            </div>
        @endif
    </form>
</dialog>