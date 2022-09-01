<div class="max-w-screen-sm mx-auto">
    <x-page-header title="Create Product" back/>
    
    @if ($com = lw('app.product.update.overview'))
        @livewire($com, compact('product'))
    @endif
</div>
