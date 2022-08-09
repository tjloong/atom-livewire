<div
    x-data="{ variant: @js(head($variants)) }"
    class="bg-white shadow rounded-lg border overflow-hidden flex flex-col w-full {{
        $attributes->get('class') ?? 'min-w-[300px] max-w-[500px]'
    }}"
>
    <div class="flex-grow p-6">
        <div class="flex flex-col gap-4 h-full">
            @isset($header)
                {{ $header }}
            @else
                <div class="grid">
                    <div class="font-semibold text-xl">
                        {{ data_get($plan, 'name') }}
                    </div>

                    @if ($trial)
                        <div class="text-gray-500 font-medium">
                            {{ $trial }} {{ str('Day')->plural($trial) }} Trial
                        </div>
                    @endif
                </div>
            @endif
    
            @isset($price)
                {{ $price }}
            @else
                <div class="grid gap-1">
                    <div class="flex gap-3">
                        @foreach ($prices as $val)
                            <div x-show="variant === '{{ data_get($val, 'recurring') }}'" class="flex items-center gap-2">
                                <span class="font-medium">{{ data_get($val, 'currency') }}</span>
                                <span class="text-4xl font-extrabold">{{ currency(data_get($val, 'amount')) }}</span>
                            </div>
                        @endforeach

                        @if (count($variants) === 1)
                            <div class="font-medium">{{ head($variants) }}</div>
                        @elseif (count($variants) > 1)
                            <div class="flex items-center flex-wrap">
                                @foreach ($variants as $var)
                                    <div 
                                        x-bind:class="variant === '{{ $var }}' 
                                            ? 'py-1 px-2 rounded bg-theme-dark text-white text-center font-semibold' 
                                            : 'cursor-pointer px-2'"
                                        x-on:click="variant = '{{ $var }}'"
                                        class="text-sm font-medium"
                                    >
                                        {{ str()->headline($var) }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
    
            @isset($body)
                {{ $body }}
            @else
                <div class="text-gray-500 font-medium">{{ data_get($plan, 'excerpt') }}</div>
        
                @if ($feats = data_get($plan, 'features'))
                    <div class="flex-grow">
                        <div class="grid gap-2">
                            @foreach ($feats as $feat)
                                <div class="flex gap-2 items-center">
                                    <x-icon name="check" class="text-green-500" size="16px"/> {{ $feat }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @isset($cta)
        <div class="flex-shrink-0 bg-gray-100 p-6">
            {{ $cta }}
        </div>
    @endisset
</div>