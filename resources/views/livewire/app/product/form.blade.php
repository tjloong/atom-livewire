<x-form :header="$header">
    <x-form.group cols="2">
        <x-form.text wire:model.defer="product.name" label="Product Name"/>
        
        <x-form.field label="Product Code" name="product.code">
            <div class="flex flex-col gap-2">
                <x-form.text wire:model.defer="product.code" :label="false"/>
                <div wire:click="generateCode" class="text-sm inline-flex items-center gap-2 cursor-pointer">
                    <x-icon name="arrows-rotate" size="12px"/> Auto generate
                </div>
            </div>
        </x-form.field>

        <x-form.select wire:model="product.type" label="Product Type" :options="data_get($this->options, 'types')"/>

        <x-form.select wire:model="inputs.categories" :options="data_get($this->options, 'categories')" multiple>
            <x-slot:footlink 
                label="New Category" 
                :href="route('app.preferences', ['product-category'])"
            ></x-slot:footlink>
        </x-form.select>

        @if ($product->type === 'normal')
            <x-form.number wire:model.defer="product.stock" step=".01"/>
        @endif

        @module('taxes')
            <x-form.select wire:model="inputs.taxes" :options="data_get($this->options, 'taxes')" multiple>
                <x-slot:footlink
                    label="New Tax"
                    :href="route('app.preferences', ['tax'])"
                ></x-slot:footlink>
            </x-form.select>
        @endmodule
        
        @if ($product->type === 'variant')
            <x-form.field label="Price">
                <div class="flex items-center gap-2 text-sm">
                    <x-icon name="circle-info" size="14px" class="text-gray-400"/>
                    {{ __('Selling prices will follow each variant configuration.') }}
                </div>
            </x-form.field>
        @else
            <x-form.number wire:model.defer="product.price"/>
        @endif

        <x-form.checkbox wire:model="product.is_active" label="Product is active"/>
    </x-form.group>
</x-form>
