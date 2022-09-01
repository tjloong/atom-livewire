<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Create Product Variant" back/>

    @if ($com = lw('app.product.variant.form'))
        @livewire($com, compact('productVariant'))
    @endif
</div>
