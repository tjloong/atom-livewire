@if ($attributes->get('sort'))
<th 
    x-data="{ 
        isSorted () { return $wire.get('sortBy') === '{{ $attributes->get('sort') }}' },
        sort () {
            if ($wire.get('sortBy') === '{{ $attributes->get('sort') }}') {
                $wire.set('sortOrder', $wire.get('sortOrder') === 'asc' ? 'desc' : 'asc')
            }
            else $wire.set('sortOrder', 'asc')

            $wire.set('sortBy', '{{ $attributes->get('sort') }}')
        },
    }"
    x-bind:class="{ 'font-medium underline': isSorted() }"
>
    <a
        {{ $attributes->class([
            'text-gray-900 flex items-center space-x-1',
            'justify-end' => $attributes->get('align') === 'right',
            'justify-start' => $attributes->get('align') === 'left',
            'justify-center' => $attributes->get('align') === 'center',
        ]) }}
        x-on:click.prevent="sort()"
    >
        <div>{{ $slot }}</div>
        <x-icon
            x-bind:name="$wire.get('sortOrder') === 'desc' ? 'chevron-up' : 'chevron-down'" 
            size="18px"
            x-show="isSorted()"
        />
    </a>
</th>

@elseif ($attributes->get('checkbox'))
<th {{ $attributes->merge(['class' => 'px-3', 'width' => '30']) }}>
    <x-input.checkbox wire:change="checkAll($event.target.checked)"/>
</th>

@else
<th {{ $attributes->class([
    'text-gray-500',
    'text-right' => $attributes->get('align') === 'right',
    'text-center' => $attributes->get('align') === 'center',
]) }}>
    {{ $slot }}
</th>

@endif