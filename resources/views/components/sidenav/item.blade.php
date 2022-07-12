@if ($attributes->get('disabled'))
    <div class="py-2 px-3 flex items-center gap-2 text-gray-400">
        @if ($icon = $attribute->get('icon'))
            <x-icon :name="$icon" :type="$attributes->get('icon-type') ?? 'regular'"/>
        @endif

        @if ($label = $attributes->get('label')) {{ __($label) }}
        @else {{ $slot }}
        @endif
    </div>
@else
    <div 
        x-data="{ get active () { return value === '{{ $attributes->get('name') }}' }}"
        x-on:click="select('{{ $attributes->get('name') }}')"
        x-bind:class="active
            ? 'font-bold text-theme-inverted bg-theme rounded-l rounded-r md:border-r-2 md:border-theme-dark md:text-theme-dark md:bg-gray-200 md:rounded-r-none'
            : ('font-medium text-gray-600 hover:font-bold md:flex ' + (!show && 'hidden'))
        "
        class="py-2 px-4 flex items-center gap-2 cursor-pointer"
        {{ $attributes->except(['name', 'active']) }}
    >
        @if ($icon = $attributes->get('icon'))
            <div
                x-bind:class="active ? 'md:text-theme' : 'md:text-gray-400'"
                class="shrink-0 flex items-center justify-center"
            >
                <x-icon :name="$icon" :type="$attributes->get('icon-type') ?? 'regular'" size="18px"/>
            </div>
        @elseif ($attributes->has('bullet'))
            <div
                x-bind:class="active 
                    ? 'w-2 h-2 bg-theme-dark ring-2 ring-offset-2 ring-theme mr-1' 
                    : 'w-3 h-3 border-2 border-gray-400'
                "
                class="shrink-0 rounded-full"
            ></div>
        @endif

        <div class="grow">
            @if ($label = $attributes->get('label')) {{ __($label) }}
            @else {{ $slot }}
            @endif
        </div>

        <div x-show="active && !show" class="shrink-0 flex items-center justify-center md:hidden">
            <x-icon name="chevron-down"/>
        </div>
    </div>
@endif
