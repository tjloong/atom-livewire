@php
$id = $attributes->get('id') ?? $this->getName() ?? $this->id;
$stacked = $attributes->get('stacked', false);
$locked = $attributes->get('locked', false);
$heading = $heading ?? $attributes->getAny('title', 'heading');
$form = $attributes->hasLike('wire:submit*', 'x-on:submit*', 'x-recaptcha:submit*');
$element = $form ? 'form' : 'div';
$except = ['locked', 'title', 'heading', 'class', 'stacked'];
@endphp

<{{ $element }}
    x-cloak
    x-data="overlay({{ Js::from($id) }})"
    x-show="show"
    x-transition.opacity.duration.200
    x-on:keydown.escape.window.stop="close()"
    class="overlay dialog fixed inset-0 overflow-auto z-50"
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

            <div class="grow flex gap-3">
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
                    <div {{ $buttons->attributes->class(['shrink-0 flex items-center gap-2']) }}>
                        {{ $buttons }}
                    </div>
                @endif
            </div>
        </div>

        <div class="grow overflow-auto md:first:rounded-tl-xl md:last:rounded-bl-xl">
            @if ($stacked)
                <div class="flex flex-col divide-y">
                    {{ $slot }}
                </div>
            @else
                {{ $slot }}
            @endif
        </div>
    </div>
</{{ $element }}>
