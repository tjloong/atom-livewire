<div
    x-data="{ variant: @js(head($variants)) }"
    class="bg-white shadow rounded-lg border overflow-hidden flex flex-col"
>
    <div class="flex-grow p-6">
        <div class="flex flex-col gap-6 h-full">
            @isset($header)
                {{ $header }}
            @else
                <div>
                    @if (count($variants) > 1)
                        <div class="flex gap-2 items-center float-right">
                            @foreach ($variants as $var)
                                <div 
                                    x-bind:class="variant === '{{ $var }}' ? 'bg-theme-dark text-white font-semibold' : 'cursor-pointer bg-gray-200'"
                                    x-on:click="variant = '{{ $var }}'"
                                    class="text-sm py-1 px-2 rounded"
                                >
                                    {{ $var }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="font-semibold text-xl">{{ $plan['name'] }}</div>
                </div>
            @endif
    
            @isset($price)
                {{ $price }}
            @else
                <div class="grid gap-1">
                    @foreach ($prices as $val)
                        <div x-show="variant === '{{ $val['recurring'] }}'" class="flex gap-2">
                            <span class="font-medium">{{ $val['currency'] }}</span>
                            <span class="text-4xl font-extrabold">{{ currency($val['amount']) }}</span>
                            <span class="font-medium self-end">{{ $val['recurring'] }}</span>
                        </div>
                    @endforeach
                    
                    @if ($trial)
                        <div class="text-sm text-gray-500 font-medium">{{ $trial }} {{ str('Day')->plural($trial) }} Trial</div>
                    @endif
                </div>
            @endif
    
            @isset($body)
                {{ $body }}
            @else
                <div class="text-gray-500 font-medium">{{ $plan['excerpt'] }}</div>
        
                @if ($feats = $plan['features'] ?? null)
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