<div class="max-w-screen-xl mx-auto">
    <x-page-header :title="$product->name" :subtitle="$product->code" back>
        @can('product.delete')
            <x-button.delete inverted
                title="Delete Product"
                message="Are you sure to DELETE this product?"
            />
        @endcan
    </x-page-header>

    <div class="flex flex-col gap-6 md:flex-row">
        <div class="md:w-1/4">
            <x-sidenav>
                @foreach (collect($this->tabs)->filter() as $item)
                    <x-sidenav.item 
                        :label="data_get($item, 'label')"
                        :href="route('app.product.update', [$product->id, 'tab' => data_get($item, 'slug')])"
                        :count="data_get($item, 'count')"
                        :icon="false"
                    />
                @endforeach
            </x-sidenav>
        </div>

        <div class="md:w-3/4">
            @if ($com = data_get(tabs($this->tabs, $tab), 'livewire'))
                @if (is_string($com)) @livewire(lw($com), compact('product'), key($tab))
                @else 
                    @livewire(
                        lw(data_get($com, 'name')), 
                        array_merge(compact('product'), data_get($com, 'data')),
                        key($tab),
                    )
                @endif
            @endif
        </div>
    </div>
</div>
