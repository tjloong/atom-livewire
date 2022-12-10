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

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav wire:model="tab">
                @foreach (collect($this->tabs)->filter() as $item)
                    <x-sidenav.item 
                        :name="data_get($item, 'slug')" 
                        :label="data_get($item, 'label')"
                        :count="data_get($item, 'count')"
                        :icon="data_get($item, 'icon')"
                    />
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:w-3/4">
            @if ($component = data_get(collect($this->tabs)->firstWhere('slug', $tab), 'livewire'))
                @livewire(lw($component), compact('product'), key($tab))
            @else
                @livewire(lw('app.product.form.'.$tab), compact('product'), key($tab))
            @endif
        </div>
    </div>
</div>
