@php $plan = $attributes->get('plan') @endphp
@php $prices = data_get($plan, 'prices') @endphp

<div
    x-cloak
    x-data="{ price: {{ json_encode(collect($prices)->first()) }} }"
    {{ $attributes->class([
        'flex flex-col overflow-hidden',
        $attributes->get('class', 'bg-white shadow border rounded-lg'),
    ])->except('plan') }}
>
    <div class="grow p-6 flex flex-col gap-4">
        <div class="shrink-0">
            <h2 class="text-2xl text-gray-700 font-bold">{{ $plan->name }}</h2>
            <div class="text-gray-500 font-medium">{{ $plan->description }}</div>
        </div>

        @if ($plan->trial)
            <div class="shrink-0">
                {{ __(':count '.str('day')->plural($plan->trial).' trial', ['count' => $plan->trial]) }}
            </div>
        @endif
    
        <div class="shrink-0 flex items-center gap-2">
            @foreach ($prices as $price)
                <div x-show="price.code === @js(data_get($price, 'code'))" class="text-2xl font-medium">
                    {{ currency(data_get($price, 'amount'), data_get($plan, 'currency')) }}
                </div>
            @endforeach
    
            @if (count($prices) > 1)
                <x-dropdown>
                    <x-slot:anchor class="text-lg">
                        / <span x-text="price.valid_name"></span> <x-icon name="chevron-down" size="12"/>
                    </x-slot:anchor>
    
                    @foreach ($prices as $price)
                        <x-dropdown.item :label="data_get($price, 'valid_name')" x-on:click="price = {{ json_encode($price) }}"/>
                    @endforeach
                </x-dropdown>
            @endif
        </div>

        @if ($features = data_get($plan, 'features'))
            <div class="grow flex flex-col gap-2">
                @foreach ($features as $feat)
                    <div class="flex gap-2">
                        @if (str($feat)->startsWith('x ')) <x-icon name="circle-xmark" class="shrink-0 text-gray-400"/>
                        @else <x-icon name="circle-check" class="shrink-0 text-green-500"/>
                        @endif
    
                        <div class="self-center">
                            {!! str($feat)->replaceFirst('x ', '') !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{ $slot }}
    </div>

    @if (isset($foot) && $foot->isNotEmpty())
        <div class="bg-gray-100 p-4">
            {{ $foot }}
        </div>
    @endisset
</div>