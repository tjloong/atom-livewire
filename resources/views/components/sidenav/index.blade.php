<div 
    x-data="{
        value: @entangle($attributes->wire('model')),
        show: false,
        label: null,
    }"
    x-on:input="label = $event.detail.label"
    class="sidenav flex flex-col">
    {{-- mobile --}}
    <div x-on:click="show = !show" class="flex items-center gap-3 cursor-pointer md:cursor-auto md:hidden">
        <x-icon name="bars" class="text-gray-500"/>

        @isset($heading) {{ $heading }}
        @elseif($attributes->get('heading')) <div class="text-xl font-bold">{{ __($attributes->get('heading')) }}</div>
        @else <div x-text="label" class="font-semibold"></div>
        @endisset
    </div>

    {{-- desktop --}}
    @isset($heading) 
        <div class="hidden mb-4 md:block">
            {{ $heading }}
        </div>
    @elseif($attributes->get('heading')) 
        <div class="hidden mb-4 md:block">
            <div class="text-xl font-bold">{{ __($attributes->get('heading')) }}</div>
        </div>
    @endisset

    <div x-show="screensize('sm') ? show : true" x-collapse class="flex flex-col">
        {{ $slot }}
    </div>
</div>
