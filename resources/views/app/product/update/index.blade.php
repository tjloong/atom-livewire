<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="$product->name" :subtitle="'#'.$product->code" back>
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
                        :name="$item" 
                        :label="str()->headline($item)"
                    />
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:col-span-9">
            @if (
                $path = $tab === 'variants'
                    ? 'app/product-variant/listing'
                    : 'app/product/update/'.$tab
            )
                @if ($component = livewire_name($path))
                    @livewire($component, compact('product'), key($tab))
                @endif
            @endif
        </div>
    </div>
</div>
