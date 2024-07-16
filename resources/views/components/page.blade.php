@php
$id = $attributes->get('id') ?? $this->getName() ?? $this->id;
@endphp

<div
    x-cloak
    x-data="overlay({{ Js::from($id) }})"
    x-show="show"
    x-transition.opacity.duration.200
    x-bind:class="{
        'left-0 lg:left-0': nav === 'hidden',
        'left-0 lg:left-60': !nav || nav === 'lg',
    }"
    class="overlay page fixed z-1 bottom-0 right-0 overflow-auto bg-gray-50"
    {{ $attributes->merge(['id' => $id])->except('class') }}>
    <div class="min-h-full p-5 pb-20 {{ $attributes->get('class', 'max-w-screen-2xl') }} mx-auto">
        <div class="flex items-center gap-4 flex-wrap mb-5">
            <div class="grow">
                @isset ($back)
                    {{ $back }}
                @else
                    <x-button action="page-back"/>
                @endif
            </div>
    
            @if (isset($buttons) && $buttons->isNotEmpty())
                <div {{ $buttons->attributes->class(['shrink-0 flex items-center flex-wrap gap-2']) }}>
                    {{ $buttons }}
                </div>
            @endif
        </div>
    
        {{ $slot }}
    </div>
</div>
