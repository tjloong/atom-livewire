@if ($attributes->has('prev'))
    <div id="swiper-prev" class="hidden absolute top-0 bottom-0 left-0 z-10 flex items-center justify-center pl-3">
        <div class="flex text-white">
            <x-icon name="chevron-left" size="34px" class="m-auto drop-shadow hidden md:block"/>
            <x-icon name="chevron-left" size="24px" class="m-auto drop-shadow md:hidden"/>
        </div>
    </div>
@endif

@if ($attributes->has('next'))
    <div id="swiper-next" class="hidden absolute top-0 bottom-0 right-0 z-10 flex items-center justify-center pr-3">
        <div class="flex text-white">
            <x-icon name="chevron-right" size="34px" class="m-auto drop-shadow hidden md:block"/>
            <x-icon name="chevron-right" size="20px" class="m-auto drop-shadow md:hidden"/>
        </div>
    </div>
@endif
