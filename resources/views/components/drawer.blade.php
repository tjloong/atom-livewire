@php
$id = $attributes->get('id') ?? $this->getName() ?? $this->id;
$stacked = $attributes->get('stacked', false);
$locked = $attributes->get('locked', false);
$heading = $heading ?? $attributes->getAny('title', 'heading');
$element = $attributes->submitAction() ? 'form' : 'div';
$except = ['locked', 'title', 'heading', 'class', 'stacked', 'submit', 'form'];
@endphp

<div>
    <{{ $element }}
        x-cloak
        x-data="overlay({{ Js::from($id) }})"
        x-show="show"
        x-transition.opacity.duration.200
        x-on:keydown.escape.window.stop="close()"
        @if (is_string($attributes->submitAction()))
        wire:submit.prevent="{{ $attributes->submitAction() }}"
        @endif
        class="overlay drawer fixed inset-0 overflow-auto z-50"
        {{ $attributes->merge(['id' => $id])->except($except) }}>
        <div
            @if (!$locked) x-on:click="close()" @endif
            class="bg-black/80 fixed inset-0">
        </div>

        <div class="absolute top-0 bottom-0 right-0 bg-white shadow-lg flex flex-col w-full md:rounded-l-xl {{
            $attributes->get('class', 'max-w-xl')
        }}">
            <div class="shrink-0 p-4 bg-slate-50 flex gap-4 md:rounded-tl-xl">
                <div class="shrink-0">
                    <button type="button"
                        x-on:click="close()"
                        class="w-5 h-10 flex items-center justify-center p-2 text-xl text-gray-400 hover:text-black">
                        <x-icon name="back"/>
                    </button>
                </div>

                <div class="grow flex flex-wrap gap-3">
                    <div class="grow">
                        @if ($heading instanceof \Illuminate\View\ComponentSlot)
                            @if ($heading->isNotEmpty())
                                {{ $heading }}
                            @else
                                <x-heading class="h-full mb-0" :attributes="$heading->attributes" lg/>
                            @endif
                        @elseif ($heading)
                            <x-heading :title="$heading" class="h-full mb-0" lg/>
                        @endif
                    </div>

                    @if (isset($buttons) && $buttons->isNotEmpty())
                        <div {{ $buttons->attributes->class(['flex flex-wrap items-center gap-2']) }}>
                            {{ $buttons }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="drawer-body grow overflow-auto pb-10 md:first:rounded-tl-xl md:last:rounded-bl-xl">
                @if ($stacked)
                    <div class="flex flex-col divide-y">
                        {{ $slot }}
                    </div>
                @else
                    {{ $slot }}
                @endif
            </div>

            @if (isset($foot) && $foot->isNotEmpty())
                <div class="rounded-bl-xl {{ $foot->attributes->get('class', 'shrink-0 bg-gray-100 border-t p-4') }}">
                    {{ $foot }}
                </div>
            @endif
        </div>
    </{{ $element }}>

    @isset ($outside)
        {{ $outside }}
    @endisset
</div>
