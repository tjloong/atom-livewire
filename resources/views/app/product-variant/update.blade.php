<div class="max-w-screen-sm mx-auto">
    <x-page-header :title="$productVariant->name" back>
        <x-button.delete inverted
            title="Delete Product Variant"
            message="Are you sure to delete this product variant?"
        />
    </x-page-header>

    @if ($com = lw('app.product-variant.form'))
        @livewire($com, compact('productVariant'))
    @endif
</div>