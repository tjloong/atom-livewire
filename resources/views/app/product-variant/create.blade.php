<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Create Product Variant" back/>

    @if ($component = livewire_name('app/product-variant/form'))
        @livewire($component, compact('variant'))
    @endif
</div>
