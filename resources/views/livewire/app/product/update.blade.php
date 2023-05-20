<div class="{{ $product->type === 'variant' ? 'max-w-screen-xl' : 'max-w-screen-md' }} mx-auto">
    <x-page-header :title="$product->name" :subtitle="$product->code" back>
        <x-button.delete inverted
            title="Delete Product"
            message="Are you sure to DELETE this product?"
        />
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="{{ $product->type === 'variant' ? 'md:w-8/12' : 'w-full' }}">
            @livewire(lw('app.product.form'), compact('product'), key('form'))
        </div>

        @if ($product->type === 'variant')
            <div class="md:w-4/12">
                @livewire(lw('app.product.variant.listing'), compact('product'), key('variant'))
            </div>            
        @endif
    </div>
</div>
