@props([
    'container' => '3xl:max-w-screen-xl 3xl:mx-auto ' 
        . ($size === 'sm' ? 'min-h-[300px] ' : '')
        . ($size === 'lg' ? 'min-h-[500px] md:min-h-[600px] ' : '')
        . $bgcolor,
])

@if ($attributes->has('slider'))
    <div class="relative h-[500px] md:h-[600px] 3xl:max-w-screen-xl 3xl:mx-auto">
        <x-builder.slider :config="$attributes->get('slider')">
            {{ $slot }}
        </x-builder.slider>
    </div>

@elseif ($attributes->has('content'))
    <div class="{{ collect([
        'max-w-screen-xl mx-auto flex flex-col gap-8 py-12 px-6 w-full',
        $valign === 'top' ? 'justify-start' : null,
        $valign === 'center' ? 'justify-center' : null,
        $valign === 'bottom' ? 'justify-end' : null,
        $align === 'center' ? 'text-center' : null,
        $align === 'right' ? 'text-right' : null,
        $attributes->get('class'),
    ])->filter()->join(' ') }}">
        @if(isset($title) && $title->isNotEmpty())
            <h1 {{ $title->attributes->class([
                $title->attributes->get('class'),
                'text-3xl font-bold md:text-5xl' => !$title->attributes->get('class'),
                'text-gray-200' => !$title->attributes->get('class') && $overlay,
                'text-gray-900' => !$title->attributes->get('class') && !$overlay,
            ]) }}>
                {{ $title }}
            </h1>
        @endif

        @if(isset($subtitle) && $subtitle->isNotEmpty())
            <h2 {{ $subtitle->attributes->class([
                $subtitle->attributes->get('class'),
                'text-2xl font-semibold' => !$subtitle->attributes->get('class'),
                'text-gray-200' => !$subtitle->attributes->get('class') && $overlay,
                'text-gray-700' => !$subtitle->attributes->get('class') && !$overlay,
            ]) }}>
                {{ $subtitle }}
            </h2>
        @endif

        @if($slot->isNotEmpty())
            <div class="text-lg font-medium {{ $overlay ? 'text-gray-200' : 'text-gray-600' }}">
                {{ $slot }}
            </div>
        @endif

        @if(isset($cta) && $cta->isNotEmpty())
            <div {{ $cta->attributes->class([
                $cta->attributes->get('class'),
                'inline-flex items-center space-x-3' => !$cta->attributes->get('class'),
                'justify-center' => !$cta->attributes->get('class') && $align === 'center',
                'justify-end' => !$cta->attributes->get('class') && $align === 'right',
            ]) }}>
                {{ $cta }}
            </div>
        @endif
    </div>

@elseif ($image['position'] === 'bg')
    <div
        class="{{ $container }} relative bg-center bg-no-repeat bg-cover flex flex-col {{ $attributes->get('class') }}"
        style="background-image: url({{ $image['url'] }});"
    >
        @if ($overlay) <div class="absolute inset-0 bg-black/30"></div> @endif

        {{ $slot }}
    </div>

@elseif (in_array($image['position'], ['left', 'right']))
    <div class="{{ $container }} {{ $attributes->get('class') }}">
        <div class="relative grid gap-8 md:grid-cols-2">
            <img 
                src="{{ $image['url'] }}" 
                class="w-full h-full relative object-cover {{ $image['position'] === 'right' ? 'order-last' : '' }}" 
                width="1200" 
                height="700" 
                alt="{{ $image['alt'] }}"
            >
    
            {{ $slot }}
        </div>
    </div>

@elseif (in_array($image['position'], ['top', 'bottom']))
    <div class="{{ $container }} {{ $attributes->get('class') }}">
        <div class="relative grid gap-6">
            {{ $slot }}

            <div class="max-w-screen-xl mx-auto px-4 relative {{ $image['position'] === 'top' ? 'order-first' : 'order-last' }}">
                <img 
                    src="{{ $image['url'] }}" 
                    class="rounded-lg drop-shadow" 
                    width="1200" 
                    height="700" 
                    alt="{{ $image['alt'] }}"
                >
            </div>
        </div>
    </div>

@else
    <div class="{{ $container }} relative h-px">
        @if ($overlay) <div class="absolute inset-0 bg-black/30"></div> @endif

        {{ $slot }}
    </div>
@endif
