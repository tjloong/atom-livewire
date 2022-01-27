@if ($attributes->has('label'))
    <div class="text-xs text-gray-400 font-medium uppercase pt-4 pb-2 px-3">
        {{ $slot }}
    </div>

@elseif ($attributes->has('item'))
    <div
        @if ($attributes->has('href'))
            x-data="{ active: @js(Str::is($attributes->get('href') . '*', url()->current())) }"
            x-on:click="show = !show; (!active && (window.location = '{{ $attributes->get('href') }}'))"
        @else
            x-data="{
                name: @js($attributes->get('name') ?? Str::slug($slot->toHtml())),
                get active () { return this.value === this.name },
            }"
            x-on:click="show = !show; (!active && $dispatch('input', name))"
        @endif
        x-bind:class="{
            'font-bold text-theme bg-white drop-shadow border md:drop-shadow-none md:border-0': active && !show,
            'font-bold text-theme bg-white': active && show,
            'hidden font-medium text-gray-600 hover:bg-gray-100 hover:font-bold md:block': !active && !show,
            'font-medium text-gray-600 hover:bg-gray-100 hover:font-bold': !active && show,
        }"
        wire:loading.class="pointer-events-none"
        class="py-2 px-3 flex items-center space-x-2 rounded-md cursor-pointer"
        {{ $attributes->except('name', 'href') }}
    >
        @if ($attributes->has('icon'))
            <div
                x-bind:class="active ? 'text-theme' : 'text-gray-400'"
                class="flex-shrink-0 flex items-center justify-center"
            >
                <x-icon name="{{ $attributes->get('icon') }}" type="{{ $attributes->get('icon-type') ?? 'regular' }}" size="20px"/>
            </div>
        @endif

        <div class="flex-grow">
            {{ $slot }}
        </div>

        <div x-show="active && !show" class="flex-shrink-0 flex items-center justify-center md:hidden">
            <x-icon name="chevron-down"/>
        </div>
    </div>

@else
    <div
        @if ($attr = $attributes->wire('model')->value()) x-data="{ value: @entangle($attr), show: false }"
        @else x-data="{ show: false }"
        @endif
        {{ $attributes }}
    >
        <div x-bind:class="show && 'fixed inset-0 z-20 md:static'">
            <div x-show="show" class="absolute inset-0 bg-black opacity-50 md:hidden"></div>
            <div
                x-on:click="show = false"
                x-bind:class="show && 'absolute inset-0 px-6 pt-6 pb-16 md:static md:p-0'"
            >
                <div
                    x-on:click.stop
                    x-bind:class="show && 'divide-y bg-white rounded-md drop-shadow max-w-sm mx-auto md:bg-transparent md:divide-none md:drop-shadow-none'" 
                    class="grid"
                >
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

@endif