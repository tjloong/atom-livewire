@if ($attributes->has('prev'))
    <div id="swiper-prev" class="hidden absolute top-0 bottom-0 left-0 z-10 flex items-center justify-center md:pl-3">
        <div class="bg-gray-100 flex items-center justify-center drop-shadow rounded-r-md py-1 md:rounded-full md:px-1">
            <x-icon name="chevron-left" size="32px" class="hidden md:block"/>
            <x-icon name="chevron-left" size="20px" class="md:hidden"/>
        </div>
    </div>
@endif

@if ($attributes->has('next'))
    <div id="swiper-next" class="hidden absolute top-0 bottom-0 right-0 z-10 flex items-center justify-center md:pr-3">
        <div class="bg-gray-100 flex items-center justify-center drop-shadow rounded-l-md py-1 md:rounded-full md:px-1">
            <x-icon name="chevron-right" size="32px" class="hidden md:block"/>
            <x-icon name="chevron-right" size="20px" class="md:hidden"/>
        </div>
    </div>
@endif
