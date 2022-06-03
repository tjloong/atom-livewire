<th 
    class="py-1 px-2 bg-gray-100 font-medium text-sm uppercase border-b border-gray-200 leading-6 tracking-wider"
    {{ $attributes->except(['sort', 'label']) }}
>
    @if ($sort = $attributes->get('sort'))
        <div 
            x-data="tableTh('{{ $attributes->get('sort') }}')" 
            x-bind:class="sorted ? 'bg-gray-200 rounded px-3' : 'px-2'"
            class="flex items-center gap-1 py-1"
        >
            <a x-on:click.prevent="sort()" {{ $attributes->class(['grow', 'text-left', 'text-gray-600']) }}">
                @if ($label = $attributes->get('label')) {{ __($label) }}
                @else {{ $slot }}
                @endif
            </a>
            <x-icon x-show="sorted && $wire.get('sortOrder') === 'desc'" name="chevron-up" size="xs"/>
            <x-icon x-show="sorted && $wire.get('sortOrder') === 'asc'" name="chevron-down" size="xs"/>
        </div>
    @else
        <div {{ $attributes->class(['text-gray-500 py-1 px-2', 'text-left']) }}">
            @if ($label = $attributes->get('label')) {{ __($label) }}
            @else {{ $slot }}
            @endif
        </div>
    @endif
</th>
