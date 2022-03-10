<div 
    @if (count($prices))
        x-data="{ recurring: '{{ head($prices)['recurring'] }}' }"
    @endif
    class="bg-white shadow rounded-lg border overflow-hidden flex flex-col"
>
    <div class="flex-grow p-6">
        <div class="flex flex-col gap-6 h-full">
            @isset($header)
                {{ $header }}
            @else
                <div>
                    @if (count($prices))
                        <div class="flex gap-2 items-center float-right">
                            @foreach ($prices as $prc)
                                <div 
                                    x-bind:class="recurring === '{{ $prc['recurring'] }}' ? 'bg-theme-dark text-white font-semibold' : 'cursor-pointer bg-gray-200'"
                                    x-on:click="recurring = '{{ $prc['recurring'] }}'"
                                    class="text-xs py-1 px-2 rounded"
                                >
                                    {{ $prc['recurring'] }}
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
                    @foreach ($prices as $prc)
                        <div x-show="recurring === '{{ $prc['recurring'] }}'" class="flex gap-2">
                            <span class="font-medium">{{ $prc['currency'] }}</span>
                            <span class="text-4xl font-extrabold">{{ currency($prc['amount']) }}</span>
                            <span class="font-medium self-end">{{ $prc['recurring'] }}</span>
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
        
                <div class="flex-grow">
                    <div class="grid gap-2">
                        @foreach ($plan['features'] as $feat)
                            <div class="flex gap-2 items-center">
                                <x-icon name="check" color="green"/> {{ $feat }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="flex-shrink-0 bg-gray-100 p-6">
        @foreach ($prices as $prc)
            <div x-show="recurring === '{{ $prc['recurring'] }}'">
                @if ($subscribed = $prc['is_subscribed'] ?? false)
                    <div class="text-gray-500 font-medium flex items-center gap-1">
                        <x-icon name="check"/> Currently Subscribed
                    </div>
                @else
                    <x-button 
                        href="{{ 
                            $cta['href']
                            .(strpos($cta['href'], '?') ? '&' : '?')
                            .http_build_query(['plan' => $plan['slug'], 'price' => $prc['id']])
                        }}" 
                        size="md" 
                        class="w-full" 
                        :color="$cta['color']" 
                        :icon="$cta['icon']" 
                        :icon-type="$cta['icon_type']"
                    >
                        {{ $cta['text'] }}
                    </x-button>
                @endif
            </div>
        @endforeach
    </div>
</div>