<div
    x-data="{ show: false }"
    x-on:click.away="show = false"
    {{ $attributes->merge(['class' => 'grid gap-2 py-4']) }}
>
    <div x-on:click="show = true" class="font-bold flex gap-3 cursor-pointer">
        <div class="grow">
            @if ($question = $attributes->get('question')) {!! nl2br(__($question)) !!}
            @elseif (isset($question)) {{ $question }}
            @endif
        </div>
        <x-icon x-show="show" name="chevron-up" size="18px" class="shrink-0 text-gray-400"/>
        <x-icon x-show="!show" name="chevron-down" size="18px" class="shrink-0 text-gray-400"/>
    </div>
    
    <div x-show="show" class="text-gray-500 font-medium prose max-w-none">
        @if ($answer = $attributes->get('answer')) {!! nl2br(__($answer)) !!}
        @else {{ $slot }}
        @endif
    </div>

    @if ($excerpt = $attributes->get('excerpt'))
        <div x-show="!show" class="text-gray-500 font-medium">
            {{ __($excerpt) }}
        </div>
    @endif
</div>
