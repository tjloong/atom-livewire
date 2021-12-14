@props([
    'gridCols' => [
        '1' => 'md:grid-cols-1',
        '2' => 'md:grid-cols-2',
        '3' => 'md:grid-cols-3',
        '4' => 'md:grid-cols-4',
    ]
])

<div class="mx-auto py-20 px-6 grid space-y-6 {{ $cols > 1 ? 'max-w-screen-xl' : 'max-w-screen-lg' }}">
    <div class="
        grid space-y-4
        {{ $align === 'center' ? 'text-center' : '' }}
        {{ $align === 'right' ? 'text-right' : '' }}
    ">
        @isset($title)
            <div class="text-3xl font-bold">{{ $title }}</div>
        @endisset
    
        @isset($subtitle)
            <div class="font-medium text-gray-500">{{ $subtitle }}</div>
        @endisset
    </div>

    <div class="
        grid divide-y
        {{ $cols > 1 ? "$gridCols[$cols] md:gap-10 md:divide-y-0" : 'divide-y' }}
    ">
        @foreach ($items as $item)
            <div
                x-data="{ show: false }"
                x-on:click="show = true"
                x-on:click.away="show = false"
                class="flex flex-col space-y-2 py-6 cursor-pointer {{ $cols > 1 ? 'md:py-0' : '' }}"
            >
                <div class="font-bold flex space-x-2">
                    <div class="flex-grow">{{ $item->question }}</div>
                    <x-icon x-bind:name="show ? 'chevron-up' : 'chevron-down'" size="24px" class="text-gray-400"/>
                </div>
                
                <div x-show="show" class="text-sm text-gray-500 font-medium prose prose-sm max-w-none">
                    {!! $item->answer !!}
                </div>

                <div x-show="!show" class="text-sm text-gray-500 font-medium">
                    {{ $item->excerpt }}
                </div>
            </div>
        @endforeach
    </div>
</div>