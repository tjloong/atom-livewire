@php
    $configs = [
        'dots' => $attributes->get('dots', true),
        'arrows' => $attributes->get('arrows', true),
        'autoplay' => $attributes->get('autoplay', true),
        'autoplaySpeed' => $attributes->get('speed', 3000),
        'slidesToShow' => $attributes->get('slides-to-show', 1),
        'slidesToScroll' => $attributes->get('slides-to-scroll', 1),
        'adaptiveHeight' => $attributes->get('adaptive-height', true),
    ];

    $hasThumbnails = isset($thumbnails);
    $thumbnailsConfigs = $hasThumbnails ? [
        'dots' => $thumbnails->attributes->get('dots', false),
        'arrows' => $thumbnails->attributes->get('arrows', false),
        'slidesToShow' => $thumbnails->attributes->get('slides-to-show', 3),
        'slidesToScroll' => $thumbnails->attributes->get('slides-to-scroll', 1),
    ] : [];

    $except = [
        'arrows', 'dots', 'autoplay', 'speed',
        'slides-to-show', 'slides-to-scroll', 'adaptive-height',
    ];
@endphp

<div
    x-cloak
    x-data="{
        configs: @js($configs),
        thumbnailsConfigs: @js($thumbnailsConfigs),
        hasThumbnails: @js($hasThumbnails),

        startSlides () {
            const slides = $($el).find('.slides')

            slides.slick({
                ...this.configs,
                prevArrow: '.slick-prev',
                nextArrow: '.slick-next',
                asNavFor: this.hasThumbnails ? '.slick-thumbnails' : null,
            })

            slides.on('init', function (event, slick) {
                $dispatch('slides-init', { event, slick })
            })
        
            slides.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                $dispatch('slides-before-changed', { event, slick, currentSlide, nextSlide })
            })
        
            slides.on('afterChange', function (event, slick, currentSlide) {
                $dispatch('slides-after-changed', { event, slick, currentSlide })
            })
        },

        startThumbnails () {
            if (!this.hasThumbnails) return

            const tn = $($el).find('.thumbnails')

            tn.slick({
                ...this.thumbnailsConfigs,
                asNavFor: '.slick-slides',
                centerMode: true,
                centerPadding: '40px',
                focusOnSelect: true,
            })

            tn.on('init', function (event, slick) {
                $dispatch('thumbnails-init', { event, slick })
            })
        
            tn.on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                $dispatch('thumbnails-before-changed', { event, slick, currentSlide, nextSlide })
            })
        
            tn.on('afterChange', function (event, slick, currentSlide) {
                $dispatch('thumbnails-after-changed', { event, slick, currentSlide })
            })
        },
    }"
    x-init="startSlides(); startThumbnails(); $($el).removeClass('hidden')"
    class="hidden"
    {{ $attributes->except($except) }}>
    <div class="relative">
        @if (get($configs, 'arrows'))
            {!! $leftArrow !!}
            {!! $rightArrow !!}
        @endif

        <div class="slides">
            {{ $slot }}
        </div>
    </div>

    @if ($hasThumbnails)
        <div class="relative hidden md:block">
            @if (get($thumbnailsConfigs, 'arrows'))
                {!! $leftArrow !!}
                {!! $rightArrow !!}
            @endif

            <div class="thumbnails">
                {{ $thumbnails }}
            </div>
        </div>
    @endif
</div>
