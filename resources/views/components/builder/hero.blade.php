@props([
    'bgImg' => $image && $imagePosition === 'bg' ? $image : null,
    'leftImg' => $image && $imagePosition === 'left' ? $image : null,
    'rightImg' => $image && $imagePosition === 'right' ? $image : null,
    'bottomImg' => $image && $imagePosition === 'bottom' ? $image : null,
])

@if ($attributes->has('slider'))
    <div class="relative h-[500px] md:h-[600px] 3xl:max-w-screen-xl 3xl:mx-auto">
        <x-builder.slider :config="$attributes->get('slider')">
            {{ $slot }}
        </x-builder.slider>
    </div>
@else
    <div class="relative min-h-[500px] md:min-h-[600px] 3xl:max-w-screen-xl 3xl:mx-auto {{ $attributes->get('bgcolor') }}">
        @if ($bgImg)
            <div class="absolute inset-0 {{ $overlay ? 'bg-black/30' : '' }}">
                <img src="{{ $bgImg }}" class="w-full h-full object-cover opacity-30" width="1200" height="700" alt="{{ $attributes->get('image-alt') }}">
            </div>
        @elseif ($overlay)
            <div class="absolute inset-0 bg-black/30"></div>
        @endif

        <div class="absolute inset-0">
            <div class="
                max-w-screen-xl mx-auto h-full grid
                {{ $leftImg || $rightImg ? 'gap-6 md:grid-cols-2' : '' }}
            ">
                @if ($leftImg || $rightImg)
                    <img 
                        src="{{ $leftImg ?? $rightImg }}" 
                        class="w-full h-full object-cover {{ $rightImg ? 'order-last' : '' }}" 
                        width="1200" 
                        height="700" 
                        alt="{{ $attributes->get('image-alt') }}"
                    >
                @endif
    
                <div class="
                    max-w-screen-lg mx-auto relative h-full flex flex-col gap-8 py-12 px-6
                    {{ $valign === 'top' ? 'justify-start' : '' }}
                    {{ $valign === 'center' ? 'justify-center' : '' }}
                    {{ $valign === 'bottom' ? 'justify-end' : '' }}
                    {{ $align === 'center' ? 'text-center' : '' }} 
                    {{ $align === 'right' ? 'text-right' : '' }}
                ">
                    @isset($title)
                        <h1 class="text-3xl font-bold md:text-5xl {{ $overlay ? 'text-gray-200' : 'text-gray-900' }}">
                            {{ $title }}
                        </h1>
                    @endisset
    
                    @isset($subtitle)
                        <h2 class="text-xl font-semibold md:text-2xl {{ $overlay ? 'text-gray-200' : 'text-gray-700' }}">
                            {{ $subtitle }}
                        </h2>
                    @endisset
    
                    @if($slot->isNotEmpty())
                        <p class="text-lg font-medium md:text-lg {{ $overlay ? 'text-gray-200' : 'text-gray-600' }}">
                            {{ $slot }}
                        </p>
                    @endif
    
                    @isset($cta)
                        <div class="
                            inline-flex items-center space-x-3
                            {{ $align === 'center' ? 'justify-center' : '' }}
                            {{ $align === 'right' ? 'justify-end' : '' }}
                        ">
                            {{ $cta }}
                        </div>
                    @endisset
                </div>
    
                @if ($bottomImg)
                    <img 
                        src="{{ $bottomImg }}" 
                        class="rounded-lg drop-shadow" 
                        width="1200" 
                        height="700" 
                        alt="{{ $attributes->get('image-alt') }}"
                    >
                @endif
            </div>
        </div>
    </div>
@endif
