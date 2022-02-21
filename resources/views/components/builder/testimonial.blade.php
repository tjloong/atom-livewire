@if ($attributes->has('content'))
    <div class="grid gap-1">
        <x-icon name="quote-alt-left" type="solid" size="{{ $attributes->has('small') ? '32px' : '64px' }}" class="text-gray-400 opacity-40"/>

        <div {{ $attributes->class([
            'text-gray-300' => $dark,
            'text-gray-800' => !$dark,
            'text-center' => $align === 'center',
            'text-right' => $align === 'right',
            'text-left' => $align === 'left' || !$align,
        ]) }}>
            {{ $slot }}
        </div>
    </div>

@elseif ($attributes->has('card'))
    <div {{ $attributes->class([
        'flex items-center gap-4',
        'flex-col justify-center' => $customer['align'] === 'center',
        'justify-end' => $customer['align'] === 'right',
        'justify-start' => $customer['align'] === 'left' || !$customer['align'],
    ]) }}>
        @if ($image && $image['url'])
            <figure class="flex-shrink-0 w-20 h-20 drop-shadow rounded-full overflow-hidden bg-gray-100 md:w-24 md:h-24 {{ $customer['align'] === 'right' ? 'order-last' : 'order-first' }}">
                <img 
                    src="{{ $image['url'] }}" 
                    class="w-full h-full object-cover"
                    width="150" 
                    height="150" 
                    alt="{{ $image['alt'] }}"
                >
            </figure>
        @endif

        <div class="grid">
            <div class="font-semibold {{ $dark ? 'text-gray-300' : 'text-gray-800' }}">
                {{ $customer['name'] }}
            </div>
    
            @if ($customer['designation'])
                <div class="text-sm text-gray-400 font-medium">{{ $customer['designation'] }}</div>
            @endif
    
            @if ($customer['company'])
                <div class="text-sm text-gray-400 font-medium">{{ $customer['company'] }}</div>
            @endif
        </div>
    </div>

@elseif (in_array($image['position'], ['left', 'right']))
    <div class="{{ $attributes->get('class') }}">
        <div class="max-w-screen-xl mx-auto px-6">
            <div class="flex flex-col items-center gap-10 md:flex-row">
                <div class="flex-shrink-0 mx-auto {{ $image['position'] === 'left' ? 'order-first' : 'order-last' }}">
                    @if ($image['url'])
                        <figure class="drop-shadow overflow-hidden w-60 h-60 md:w-80 md:h-80 {{ $image['circle'] ? 'rounded-full' : 'rounded-xl' }}">
                            <img src="{{ $image['url'] }}" class="w-full h-full object-cover">
                        </figure>
                    @endif
                </div>

                <div class="flex-grow">
                    <div class="grid gap-4">
                        <x-builder.testimonial content :align="$align" :dark="$dark" class="text-2xl font-medium">
                            {{ $slot }}
                        </x-builder.testimonial>

                        @if ($customer)
                            <x-builder.testimonial card :customer="$customer" :dark="$dark"/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif (in_array($image['position'], ['top', 'bottom']))
    <div class="{{ $attributes->get('class') }}">
        <div class="max-w-screen-lg mx-auto px-6">
            <div class="grid gap-4">
                <div class="{{ $image['position'] === 'top' ? 'order-first' : 'order-last' }}">
                    <x-builder.testimonial card :customer="$customer" :dark="$dark" :image="$image"/>
                </div>

                <x-builder.testimonial content :align="$align" :dark="$dark" small>
                    {{ $slot }}
                </x-builder.testimonial>
            </div>
        </div>
    </div>

@endif
