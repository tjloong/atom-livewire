@if ($attributes->has('item'))
    <div
        @if ($attributes->get('disabled'))
            x-data="{ active: false }"
            class="py-2 px-3 flex items-center gap-2 pointer-events-none text-gray-400"
        @else
            @if ($attributes->has('href'))
                @if ($attributes->has('active')) x-data="{ active: @js($attributes->get('active')) }"
                @else x-data="{ active: @js(str()->is($attributes->get('href') . '*', url()->current())) }"
                @endif
                x-on:click="show = !show; (!active && (window.location = '{{ $attributes->get('href') }}'))"
            @else
                x-data="{
                    name: @js($attributes->get('name') ?? str()->slug($slot->toHtml())),
                    @if ($attributes->has('active')) active: @js($attributes->get('active')),
                    @else get active () { return this.value === this.name },
                    @endif
                }"
                x-on:click="show = !show; (!active && $dispatch('input', name))"
            @endif
            x-bind:class="active
                ? 'font-bold text-theme-dark bg-theme-light rounded-l rounded-r md:border-r-2 md:border-theme-dark md:bg-gray-200 md:rounded-r-none'
                : ('font-medium text-gray-600 hover:font-bold md:flex ' + (!show && 'hidden'))
            "
            wire:loading.class="pointer-events-none"
            class="py-2 px-3 flex items-center gap-2 cursor-pointer last:mb-4"
            {{ $attributes->except('name', 'href') }}
        @endif
    >
        @if ($icon = $attributes->get('icon') ?? null)
            <div
                x-bind:class="active ? 'text-theme' : 'text-gray-400'"
                class="flex-shrink-0 flex items-center justify-center"
            >
                <x-icon 
                    name="{{ $icon }}" 
                    type="{{ $attributes->get('icon-type') ?? 'regular' }}" 
                    size="18px"
                />
            </div>
        @elseif ($attributes->has('bullet'))
            <div
                x-bind:class="active 
                    ? 'w-2 h-2 bg-theme-dark ring-2 ring-offset-2 ring-theme mr-1' 
                    : 'w-3 h-3 border-2 border-gray-400'
                "
                class="flex-shrink-0 rounded-full"
            ></div>
        @endif

        <div class="flex-grow">
            {{ $slot }}
        </div>

        <div x-show="active && !show" class="flex-shrink-0 flex items-center justify-center md:hidden">
            <x-icon name="chevron-down"/>
        </div>
    </div>

@elseif ($attributes->has('group'))
    <div class="grid gap-2">
        @if ($group = $attributes->get('group') ?? null)
            <div x-bind:class="!show && 'hidden md:block'" class="text-sm text-gray-400 font-medium uppercase px-3">
                {{ $group }}
            </div>
        @endif

        <div>
            {{ $slot }}
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
                    x-bind:class="show && 'bg-white rounded-md shadow max-w-sm mx-auto p-4 md:bg-transparent md:shadow-none md:max-w-none md:p-0'"
                >
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

@endif