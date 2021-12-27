@if ($attributes->has('item') && $attributes->has('href'))
    <a href="{{ $attributes->get('href') }}" class="py-2 px-4 flex items-center gap-2 text-sm text-gray-700 hover:bg-gray-100">
        @if ($attributes->get('icon'))
            <x-icon name="{{ $attributes->get('icon') }}" type="{{ $attributes->get('icon-type') ?? 'regular' }}" size="18px"/>
        @endif

        {{ $slot }}
    </a>
@elseif ($attributes->has('item'))
    <div class="py-2 px-4 flex items-center gap-2 text-sm text-gray-700 hover:bg-gray-100">
        @if ($attributes->get('icon'))
            <x-icon name="{{ $attributes->get('icon') }}" type="{{ $attributes->get('icon-type') ?? 'regular' }}" size="18px"/>
        @endif

        {{ $slot }}
    </div>
@else
    <div
        x-cloak
        x-data="{ show: false }"
        x-on:click.away="show = false"
        x-on:close="show = false"
        class="relative"
    >
        <div x-ref="trigger" x-on:click="show = true" class="cursor-pointer">
            {{ $trigger }}
        </div>

        <div
            x-show="show"
            x-transition.opacity
            class="
                fixed inset-0 pt-12 pb-20 px-6 z-20 
                md:absolute md:inset-auto md:pt-1 md:pb-0 md:px-0 {{ $right ? 'md:right-0' : '' }}
            "
        >
            <div x-on:click="show = false" class="absolute inset-0 bg-black opacity-60 md:hidden"></div>

            <div {{ $attributes->class([
                'relative min-w-[250px] max-w-sm mx-auto bg-white border shadow-lg rounded-md py-1'
            ])->whereStartsWith('class') }}>
                {{ $slot }}
            </div>
        </div>
    </div>
@endif