@if ($providers->count())
    <div class="flex flex-col">
        @if ($divider = $attributes->get('divider'))
            <div class="flex items-center gap-3 py-6 {{ 
                $attributes->get('divider-position') === 'bottom' ? 'order-last' : '' 
            }}">
                <div class="grow bg-gray-300 h-px"></div>
                <div class="text-sm text-gray-400 font-medium">{{ __($divider) }}</div>
                <div class="grow bg-gray-300 h-px"></div>
            </div>
        @endif
    
        <div class="flex flex-col gap-2">
            @foreach ($providers as $key => $value)
                <x-button 
                    :label="__('Continue with :social', ['social' => data_get($value, 'label')])"
                    :icon="$key"
                    :class="data_get($value, 'class')"
                    :size="$size"
                    :href="route('socialite.redirect', array_merge(
                        ['provider' => $key],
                        request()->query(),
                    ))"
                />
            @endforeach
        </div>
    </div>
@endif