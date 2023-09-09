@props([
    'model' => $attributes->wire('model')->value(),
])

<div 
    x-data="{ 
        show: false,
        label: null,
        @if ($model) value: @entangle($model),
        @else value: @js($attributes->get('value')),
        @endif
    }"
    x-on:input="label = $event.detail.label"
    class="sidenav flex flex-col gap-4">
    {{-- mobile --}}
    <div x-on:click="show = !show" class="flex items-center gap-3 cursor-pointer md:cursor-auto md:hidden">
        <x-icon name="bars" class="text-gray-500"/>

        @isset($heading) {{ $heading }}
        @elseif($attributes->get('title')) <div class="text-xl font-bold">{{ __($attributes->get('title')) }}</div>
        @else <div x-text="label" class="font-semibold"></div>
        @endisset
    </div>

    {{-- desktop --}}
    <div class="hidden md:block">
        @isset($heading) {{ $heading }}
        @elseif($attributes->get('title')) <div class="text-xl font-bold">{{ __($attributes->get('title')) }}</div>
        @endisset
    </div>

    <div x-show="screensize('sm') ? show : true" x-collapse class="flex flex-col">
        {{ $slot }}
    </div>
</div>
