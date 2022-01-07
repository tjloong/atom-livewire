@if ($attributes->has('slide'))
    <div class="swiper-slide relative">
        @if ($attributes->get('image'))
            <img
                src="{{ $attributes->get('image') }}"
                class="w-full h-full object-cover"
                width="1200"
                height="500"
                alt="{{ $attributes->get('alt') }}"
            >
        @endif

        <div 
            class="
                absolute inset-0 z-10 flex p-12
                {{ $overlay ? 'bg-black/30 text-gray-200' : '' }}
                {{ $valign === 'top' ? 'items-start' : '' }}
                {{ $valign === 'center' ? 'items-center' : '' }}
                {{ $valign === 'bottom' ? 'items-end' : '' }}
            "
        >
            <div 
                class="
                    max-w-screen-xl mx-auto flex flex-col gap-4 h-max
                    {{ $align === 'center' ? 'text-center' : '' }}
                    {{ $align === 'right' ? 'text-right' : '' }}
                "
            >
                @isset($title)
                    <div class="text-4xl font-bold">
                        {{ $title }}
                    </div>                        
                @endisset

                <div>{{ $slot }}</div>

                @isset($cta)
                    <div>{{ $cta }}</div>
                @endisset
            </div>
        </div>
    </div>
@elseif (in_array($thumbsPosition, ['top', 'bottom']))
    <style>
        #swiper-thumbs .swiper-slide { opacity: 0.6; }
        #swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
    </style>

    <div x-data="slider(@js($attributes->get('config')), @js($attributes->get('thumbs-config')))" class="flex flex-col gap-4 w-full h-full">
        <div class="flex-shrink-0 hidden md:block {{ $thumbsPosition === 'bottom' ? 'order-last' : '' }}" style="height: 20%">
            <div id="swiper-thumbs" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>
            </div>
        </div>

        <div class="flex-grow" style="height: 80%">
            <div id="swiper-slider" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>
        
                <div class="swiper-pagination hidden"></div>
                <div class="swiper-scrollbar hidden"></div>
        
                <div id="swiper-prev" class="hidden absolute top-0 bottom-0 left-0 w-8 z-10 text-white flex items-center justify-center">
                    <div class="py-2 px-1 bg-black/50 rounded-r flex items-center justify-center">
                        <x-icon name="chevron-left" size="32px"/>
                    </div>
                </div>
        
                <div id="swiper-next" class="hidden absolute top-0 bottom-0 right-0 w-8 z-10 text-white flex items-center justify-center">
                    <div class="py-2 px-1 bg-black/50 rounded-l flex items-center justify-center">
                        <x-icon name="chevron-right" size="32px"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif (in_array($thumbsPosition, ['left', 'right']))
    <style>
        #swiper-thumbs .swiper-slide { opacity: 0.6; }
        #swiper-thumbs .swiper-slide-thumb-active { opacity: 1; }
    </style>

    <div x-data="slider(
        @js($attributes->get('config')), 
        @js(array_merge(['direction' => 'vertical'], $attributes->get('thumbs-config') ?? []))
    )" class="w-full h-full flex gap-4">
        <div class="flex-shrink-0 hidden md:block {{ $thumbsPosition === 'right' ? 'order-last' : '' }}" style="width: 20%">
            <div id="swiper-thumbs" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>
            </div>
        </div>

        <div class="flex-grow" style="width: 80%">
            <div id="swiper-slider" class="swiper w-full h-full">
                <div class="swiper-wrapper">{{ $slot }}</div>
        
                <div class="swiper-pagination hidden"></div>
                <div class="swiper-scrollbar hidden"></div>
        
                <div id="swiper-prev" class="hidden absolute top-0 bottom-0 left-0 w-8 z-10 text-white flex items-center justify-center">
                    <div class="py-2 px-1 bg-black/50 rounded-r flex items-center justify-center">
                        <x-icon name="chevron-left" size="32px"/>
                    </div>
                </div>
        
                <div id="swiper-next" class="hidden absolute top-0 bottom-0 right-0 w-8 z-10 text-white flex items-center justify-center">
                    <div class="py-2 px-1 bg-black/50 rounded-l flex items-center justify-center">
                        <x-icon name="chevron-right" size="32px"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div x-data="slider(@js($attributes->get('config')))" class="w-full h-full">
        <div id="swiper-slider" class="swiper w-full h-full">
            <div class="swiper-wrapper">{{ $slot }}</div>
    
            <div class="swiper-pagination hidden"></div>
            <div class="swiper-scrollbar hidden"></div>
    
            <div id="swiper-prev" class="hidden absolute top-0 bottom-0 left-0 w-8 z-10 text-white flex items-center justify-center">
                <div class="py-2 px-1 bg-black/50 rounded-r flex items-center justify-center">
                    <x-icon name="chevron-left" size="32px"/>
                </div>
            </div>
    
            <div id="swiper-next" class="hidden absolute top-0 bottom-0 right-0 w-8 z-10 text-white flex items-center justify-center">
                <div class="py-2 px-1 bg-black/50 rounded-l flex items-center justify-center">
                    <x-icon name="chevron-right" size="32px"/>
                </div>
            </div>
        </div>
    </div>
@endif
