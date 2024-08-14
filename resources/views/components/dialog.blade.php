@php
$id = $attributes->get('id') ?? $this->getName() ?? $this->id;
$locked = $attributes->get('locked', false);
$title = $title ?? $heading ?? $attributes->getAny('title', 'heading');
$element = $attributes->submitAction() ? 'form' : 'div';
@endphp

<{{ $element }}
    x-cloak
    x-data="overlay({{ Js::from($id) }})"
    x-show="show"
    x-transition.opacity.duration.200
    x-on:keydown.escape.window.stop="close()"
    @if (is_string($attributes->submitAction()))
    wire:submit.prevent="{{ $attributes->submitAction() }}"
    @endif
    class="overlay dialog fixed inset-0 overflow-auto z-50"
    {{ $attributes->merge(['id' => $id])->except('class') }}>
    <div
        @if (!$locked) x-on:click="close()" @endif
        class="bg-black/80 fixed inset-0">
    </div>

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white shadow-lg flex flex-col max-h-[95%] h-max w-full md:rounded-xl {{
        $attributes->get('class', 'md:max-w-[95%]')
    }}">
        @if ($title)
            <div class="shrink-0 bg-gray-50 border-b md:rounded-t-xl">
                @if ($title instanceof \Illuminate\View\ComponentSlot)
                    @if ($title->isEmpty())
                        <x-heading class="py-4 px-5" :attributes="$title->attributes" no-margin lg/>
                    @else
                        {{ $title }}
                    @endif
                @else
                    <x-heading class="py-4 px-5" :title="$title" no-margin lg/>
                @endif

                <div class="absolute top-4 right-4">
                    <button type="button"
                        x-on:click="close()"
                        class="flex items-center justify-center p-2 text-xl text-gray-400 hover:text-black">
                        <x-icon name="close"/>
                    </button>
                </div>
            </div>
        @endif

        <div class="grow overflow-auto">
            {{ $slot }}
        </div>

        @if ($attributes->submitAction())
            @isset ($foot)
                <div {{ $foot->attributes->merge(['class' => 'shrink-0 bg-gray-100 p-4 flex items-center gap-3 md:rounded-b-xl']) }}>
                    {{ $foot }}
                </div>
            @else
                <div class="shrink-0 bg-gray-100 p-4 md:rounded-b-xl">
                    <x-button action="submit"/>
                </div>
            @endisset
        @elseif (isset($foot) && $foot->isNotEmpty())
            <div {{ $foot->attributes->merge(['class' => 'shrink-0 bg-gray-100 p-4 md:rounded-b-xl']) }}>
                {{ $foot }}
            </div>
        @endif
    </div>
</{{ $element }}>