<main class="min-h-screen">
    <div class="max-w-screen-xl mx-auto p-4 flex flex-col gap-8 md:flex-row">
        <div class="md:w-1/2">
            @if ($this->images->count())
                <div 
                    x-data
                    x-on:slide-to-image.window="$el.querySelector('[x-ref=slider]').swiper.slideTo($event.detail)"
                    class="flex flex-col gap-2"
                >
                    <x-slider :config="[
                        'autoplay' => false,
                        'pagination' => false,
                    ]" thumbs>
                        @foreach ($this->images as $image)
                            <x-slider.slide :image="$image->url"/>
                        @endforeach
                    </x-slider>
    
                    <x-slider.thumbs>
                        @foreach ($this->images as $image)
                            <x-slider.slide :image="$image->url"/>
                        @endforeach
                    </x-slider.thumbs>
                </div>
            @endif
        </div>

        <div class="md:w-1/2 flex flex-col gap-6">
            <div>
                <h1 class="text-2xl font-semibold">
                    {{ $product->name }}
                </h1>
                <div class="text-xl font-medium">
                    {{ currency($this->variant->price ?? $product->price, $this->currency) }}
                </div>
            </div>

            @if ($cap = $product->caption)
                <div class="text-lg font-medium">
                    {{ $cap }}
                </div>
            @endif

            @if ($this->variants->count())
                <div class="flex flex-col gap-2">
                    <div class="text-sm font-medium">
                        {{ __('OPTION: :option', ['option' => str()->upper($this->variant->name)]) }}
                    </div>

                    <div class="flex items-center gap-3 flex-wrap">
                        @foreach ($this->variants as $variant)
                            <div wire:click="$set('inputs.variant_id', @js($variant->id))" class="shrink-0 rounded-lg py-1 px-3 font-medium border {{ 
                                $variant->is_disabled ? 'bg-gray-100 text-gray-400 border-gray-200' : (
                                    data_get($inputs, 'variant_id') === $variant->id 
                                        ? 'bg-theme border-theme text-theme-inverted cursor-pointer' 
                                        : 'bg-white text-theme border-theme cursor-pointer'
                                )
                            }}">
                                {{ $variant->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-3">
                <div class="shrink-0">
                    <x-form.qty wire:model.defer="inputs.qty" class="w-40 active" min="1" :label="false"/>
                </div>
                <div class="max-w-sm w-full">
                    <x-button wire:click="addToCart" label="Add To Cart" color="theme" block/>
                </div>
            </div>

            <div class="ck-content">
                {!! $product->description !!}
            </div>
        </div>
    </div>
</main>