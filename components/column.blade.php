@php
$align = $attributes->get('align', 'left');
$sort = $attributes->get('sort');
$checkbox = $attributes->get('checkbox');

$classes = $attributes->classes()
    ->add('p-1 bg-zinc-100')
    ->add('whitespace-nowrap uppercase text-sm text-zinc-500 font-medium')
    ->add('border-b border-zinc-200 leading-6 tracking-wider sticky top-0 z-1')
    ->add(match ($align) {
        'left' => 'text-left first:*:justify-start',
        'center' => 'text-center first:*:justify-center',
        'right' => 'text-right first:*:justify-end',
    })
    ->add($sort ? 'cursor-pointer text-zinc-900' : '')
    ;

$attrs = $attributes
    ->class($classes)
    ->except(['align', 'sort'])
    ;
@endphp

<th {{ $attrs }}>
    <div
        x-data="{
            get sorted () {
                return @js($sort) && sort.column === @js($sort)
            },

            get toggled () {
                return checkboxes.length >= checkables.length
            },
        }"
        x-on:click="() => {
            if (sort.column !== {{ Js::from($sort) }}) {
                sort.column = {{ Js::from($sort) }}
                sort.direction = 'asc'
            }
            else if (sort.direction === 'asc') sort.direction = 'desc'
            else sort.column = sort.direction = null
        }"
        x-bind:class="sorted && 'bg-zinc-200 rounded'"
        class="py-1.5 px-3 inline-flex items-center gap-2">
        <div class="grow">
            @if ($checkbox)
                <div
                    x-on:click="() => {
                        if (toggled) checkboxes = []
                        else checkables.forEach(row => row.dispatch('select', null, false))
                    }"
                    x-bind:class="toggled ? 'border-primary bg-primary' : 'border-zinc-300 bg-white'"
                    class="w-6 h-6 rounded-md border flex items-center justify-center cursor-pointer">
                    <x-icon check size="14" class="text-white"/>
                </div>
            @else
                {{ $slot }}
            @endif
        </div>

        @if ($sort)
            <x-icon down x-show="sorted && (sort.direction === 'asc' || !sort.direction)" class="shrink-0 text-zinc-400"/>
            <x-icon up x-show="sorted && sort.direction === 'desc'" class="shrink-0 text-zinc-400"/>
            <x-icon dropdown x-show="!sorted" class="shrink-0 text-zinc-400"/>
        @endif
    </div>
</th>
