@php
    $arrows = $attributes->get('arrows', true);
    $dots = $attributes->get('dots', true);
    $autoplay = $attributes->get('autoplay', true);
    $speed = $attributes->get('speed', 3000);
    $slidesToShow = $attributes->get('slides-to-show', null);
    $slidesToScroll = $attributes->get('slides-to-scroll', null);
    $hasThumbnails = isset($thumbnails);

    if ($hasThumbnails) {
        $thumbnailsArrows = $thumbnails->attributes->get('arrows', true);
        $thumbnailsSlidesToShow = $thumbnails->attributes->get('slides-to-show', 3);
        $thumbnailsDots = $thumbnails->attributes->get('dots', true);
    }

    $except = ['arrows', 'dots', 'autoplay', 'speed'];
@endphp

<div
    x-cloak
    x-data="{
        hasThumbnails: @js($hasThumbnails),
    }"
    x-init="() => {
        const slides = $($el).find('.slick-slides')

        slides.slick({
            dots: @js($dots),
            autoplay: @js($autoplay),
            autoplaySpeed: @js($speed),
            slidesToShow: @js($slidesToShow),
            slidesToScroll: @js($slidesToScroll),
            arrows: @js($arrows),
            prevArrow: '.slick-prev',
            nextArrow: '.slick-next',
            asNavFor: @js($hasThumbnails ? '.slick-thumbnails' : null),
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

        @if ($hasThumbnails)
        const tn = $($el).find('.slick-thumbnails')
        
        tn.slick({
            slidesToShow: @js($thumbnailsSlidesToShow),
            slidesToScroll: 1,
            asNavFor: '.slick-container',
            dots: @js($thumbnailsDots),
            centerMode: true,
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
        @endif
    }"
    {{ $attributes->except($except) }}>
    <div class="relative">
        @if ($arrows)
            {!! $leftArrow !!}
            {!! $rightArrow !!}
        @endif

        <div class="slick-slides">
            {{ $slot }}
        </div>
    </div>

    @if ($hasThumbnails)
        <div class="relative">
            @if ($thumbnailsArrows)
                {!! $leftArrow !!}
                {!! $rightArrow !!}
            @endif

            <div class="slick-thumbnails">
                {{ $thumbnails }}
            </div>
        </div>
    @endif
</div>
