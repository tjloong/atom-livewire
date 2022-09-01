<div class="max-w-screen-lg mx-auto">
    <x-page-header 
        :title="$product->name" 
        :subtitle="$product->code
            ? '#'.$product->code
            : null" 
        back
    >
        <x-button.delete inverted
            title="Delete Product"
            message="Are you sure to delete this product?"
        />
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-12">
        <div class="md:col-span-3">
            <x-sidenav wire:model="tab">
                @foreach (collect($this->tabs)->filter() as $item)
                    <x-sidenav.item 
                        :name="data_get($item, 'slug')" 
                        :label="data_get($item, 'label')"
                        :count="data_get($item, 'count')"
                    />
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if (
                $com = $tab === 'variants'
                    ? lw('app.product.variant.listing')
                    : lw('app.product.update.'.$tab)
            )
                @livewire($com, compact('product'), key($tab))
            @endif
        </div>
    </div>
</div>
