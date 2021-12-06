<section class="w-full bg-gray-200 pt-[60%] relative md:pt-[40%]">
    <div class="absolute inset-0">
        <x-swiper :config="['loop' => true]" navigation>
            @foreach ($banners as $banner)
                <x-swiper.slide
                    src="{{ $banner->image->url }}"
                    alt="{{ $banner->image->alt }}"
                    class="h-20"
                />
            @endforeach
        </x-swiper>
    </div>
</section>