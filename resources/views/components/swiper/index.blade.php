<div 
    x-data
    x-init="new Swiper(
        $refs.swiper, 
        @js(array_merge(
            [
                'direction' => 'horizontal',
                'pagination' => ['el' => '.swiper-pagination'],
                'navigation' => ['nextEl' => '.swiper-button-next', 'prevEl' => '.swiper-button-prev'],
                'scrollbar' => ['el' => '.swiper-scrollbar'],
            ], 
            $config
        ))
    )"
    class="w-full h-full"
>
    <div x-ref="swiper" {{ $attributes->merge(['class' => 'swiper w-full h-full']) }}>
        <div class="swiper-wrapper">
            {{ $slot }}
        </div>
    
        @if ($attributes->has('pagination'))
            <div class="swiper-pagination"></div>
        @endif
    
        @if ($attributes->has('navigation'))
            <div class="swiper-button-prev filter brightness-0 invert mix-blend-difference"></div>
            <div class="swiper-button-next filter brightness-0 invert mix-blend-difference"></div>
        @endif
    
        @if ($attributes->has('scrollbar'))
            <div class="swiper-scrollbar"></div>
        @endif
    </div>
</div>