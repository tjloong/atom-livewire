<x-form :header="$header">
    <x-form.group cols="2">
        <x-form.text wire:model.defer="product.name" label="Product Name"/>
        
        <x-form.text wire:model.defer="product.code" label="Product Code">
            <x-slot:button label="Generate" icon="arrows-rotate" wire:click="generateCode"></x-slot:button>
        </x-form.text>

        <x-form.slug wire:model.defer="product.slug" :url="url('product/'.$product->slug)"/>

        @if ($product->exists)
            <x-form.text :value="str($product->type)->headline()" label="Product Type" readonly/>
        @else
            <x-form.select wire:model="product.type" label="Product Type" :options="data_get($this->options, 'types')"/>
        @endif

        <x-form.select.label wire:model="inputs.categories" type="product-category" multiple/>

        @if ($product->type === 'normal')
            <x-form.number wire:model.defer="product.stock" step=".01"/>
        @endif

        @module('taxes')
            <x-form.select.tax wire:model="inputs.taxes" multiple/>
        @endmodule
        
        @if ($product->type === 'variant')
            <div class="col-span-2">
                <x-alert>
                    {{ __('Selling prices will follow each variant configuration.') }}
                </x-alert>
            </div>
        @else
            <x-form.number wire:model.defer="product.price"/>
        @endif
    </x-form.group>

    <x-form.group>
        <div>
            <x-form.checkbox wire:model="product.is_active" label="Active"/>
            <x-form.checkbox wire:model="product.is_featured" label="Featured"/>
        </div>
    </x-form.group>
</x-form>
