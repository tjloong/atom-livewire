<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$variant->name" :subtitle="$variant->code" back>
        <x-button.delete inverted
            title="Delete Product Variant"
            message="Are you sure to DELETE this product variant?"
        />
    </x-page-header>

    @livewire(lw('app.product.variant.form'), compact('variant'))
</div>