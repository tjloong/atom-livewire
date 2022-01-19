@props([
    'bgImg' => $image && $imagePosition === 'bg' ? $image : null,
    'leftImg' => $image && $imagePosition === 'left' ? $image : null,
    'rightImg' => $image && $imagePosition === 'right' ? $image : null,
    'bottomImg' => $image && $imagePosition === 'bottom' ? $image : null,
    'container' => '3xl:max-w-screen-xl 3xl:mx-auto ' 
        . ($size === 'sm' ? 'min-h-[300px]' : 'min-h-[500px] md:min-h-[600px]')
        . $bgcolor,
])

@if ($attributes->has('slider'))
    <div class="relative h-[500px] md:h-[600px] 3xl:max-w-screen-xl 3xl:mx-auto">
        <x-builder.slider :config="$attributes->get('slider')">
            {{ $slot }}
        </x-builder.slider>
    </div>

@elseif ($attributes->has('content'))
    <div class="
        max-w-screen-lg mx-auto flex flex-col gap-8 py-12 px-6
        {{ $valign === 'top' ? 'justify-start' : '' }}
        {{ $valign === 'center' ? 'justify-center' : '' }}
        {{ $valign === 'bottom' ? 'justify-end' : '' }}
        {{ $align === 'center' ? 'text-center' : '' }} 
        {{ $align === 'right' ? 'text-right' : '' }}
        {{ $attributes->get('class') }}
    ">
        @if(isset($title) && $title->isNotEmpty())
            <h1 {{ $title->attributes->class([
                'text-3xl font-bold md:text-5xl',
                'text-gray-200' => $overlay,
                'text-gray-900' => !$overlay,
            ]) }}>
                {{ $title }}
            </h1>
        @endif

        @if(isset($subtitle) && $subtitle->isNotEmpty())
            <h2 {{ $subtitle->attributes->class([
                'text-xl font-semibold md:text-2xl',
                'text-gray-200' => $overlay,
                'text-gray-700' => !$overlay,
            ]) }}>
                {{ $subtitle }}
            </h2>
        @endif

        @if($slot->isNotEmpty())
            <p class="text-lg font-medium md:text-lg {{ $overlay ? 'text-gray-200' : 'text-gray-600' }}">
                {{ $slot }}
            </p>
        @endif

        @if(isset($cta) && $cta->isNotEmpty())
            <div {{ $cta->attributes->class([
                'inline-flex items-center space-x-3',
                'justify-center' => $align === 'center',
                'justify-end' => $align === 'right',
            ]) }}>
                {{ $cta }}
            </div>
        @endif
    </div>

@elseif ($bgImg)
    <div
        class="{{ $container }} relative bg-center bg-no-repeat bg-cover flex flex-col"
        style="background-image: url({{ $bgImg }});"
    >
        @if ($overlay) <div class="absolute inset-0 bg-black/30"></div> @endif

        <x-builder.hero content class="flex-grow relative" :overlay="$overlay">
            <x-slot name="title">{{ $title ?? null }}</x-slot>
            <x-slot name="subtitle">{{ $subtitle ?? null }}</x-slot>
            <x-slot name="cta">{{ $cta ?? null }}</x-slot>
            {{ $slot }}
        </x-builder.hero>
    </div>

@elseif ($leftImg || $rightImg)
    <div class="{{ $container }}">
        <div class="relative grid gap-6 md:grid-cols-2">
            @if ($overlay) <div class="absolute inset-0 bg-black/30"></div> @endif

            <img 
                src="{{ $leftImg ?? $rightImg }}" 
                class="w-full h-full relative object-cover {{ $rightImg ? 'order-last' : '' }}" 
                width="1200" 
                height="700" 
                alt="{{ $attributes->get('image-alt') }}"
            >
    
            <x-builder.hero content class="relative" :overlay="$overlay">
                <x-slot name="title">{{ $title ?? null }}</x-slot>
                <x-slot name="subtitle">{{ $subtitle ?? null }}</x-slot>
                <x-slot name="cta">{{ $cta ?? null }}</x-slot>
                {{ $slot }}
            </x-builder.hero>
        </div>
    </div>

@elseif ($bottomImg)
    <div class="{{ $container }}">
        <div class="relative grid gap-6">
            @if ($overlay) <div class="absolute inset-0 bg-black/30"></div> @endif

            <x-builder.hero content class="relative" :overlay="$overlay">
                <x-slot name="title">{{ $title ?? null }}</x-slot>
                <x-slot name="subtitle">{{ $subtitle ?? null }}</x-slot>
                <x-slot name="cta">{{ $cta ?? null }}</x-slot>
                {{ $slot }}
            </x-builder.hero>

            <div class="max-w-screen-xl mx-auto px-4 relative">
                <img 
                    src="{{ $bottomImg }}" 
                    class="rounded-lg drop-shadow" 
                    width="1200" 
                    height="700" 
                    alt="{{ $attributes->get('image-alt') }}"
                >
            </div>
        </div>
    </div>
@endif
