<x-form :header="$header">
    <x-form.group cols="2">
        <x-form.text wire:model.defer="product.name" label="Product Name"/>
        
        <x-form.text wire:model.defer="product.code" label="Product Code">
            <x-slot:button icon="arrows-rotate" wire:click="generateCode"></x-slot:button>
        </x-form.text>

        @if (has_route('web.product.view'))
            <x-form.slug wire:model.defer="product.slug" :url="route('web.product.view', [$product->slug])"/>
        @endif

        @if ($product->exists)
            <x-form.text :value="str($product->type)->headline()" label="Product Type" readonly/>
        @else
            <x-form.select wire:model="product.type" label="Product Type" :options="data_get($this->options, 'types')"/>
        @endif

        <x-form.select.label wire:model="inputs.categories" type="product-category" multiple/>

        @module('taxes')
            <x-form.select.tax wire:model="inputs.taxes" multiple/>
        @endmodule

        @if ($product->type !== 'variant')
            <x-form.number wire:model.defer="product.price" :prefix="tenant('settings.default_currency') ?? settings('default_currency')"/>
            <x-form.number wire:model.defer="product.cost" :prefix="tenant('settings.default_currency') ?? settings('default_currency')"/>
        @endif

        @if ($product->type === 'normal')
            <x-form.number wire:model.defer="product.stock" label="Initial Stock" step=".01"/>
        @endif
    </x-form.group>

    @if ($product->exists)
        <x-form.group>
            <x-form.file accept="image/*" multiple sortable
                wire:model="inputs.images"
                wire:sorted="sort"
            />
        </x-form.group>

        <x-form.group>
            <x-form.richtext wire:model.defer="product.description" :toolbar="[
                'bold', 'italic', 'underline', 'fontSize', 'fontColor', 'link', 'bulletedList', 'numberedList',
                '|', 'alignment', 'outdent', 'indent', 'horizontalLine',
                '|', 'blockQuote', 'insertTable', 'undo', 'redo',
            ]"/>
        </x-form.group>
    @endif

    <x-form.group>
        <div>
            <x-form.checkbox wire:model="product.is_active" label="Active"/>
            <x-form.checkbox wire:model="product.is_featured" label="Featured"/>
            <x-form.checkbox wire:model="product.is_required_shipment" label="Required shipment"/>
        </div>
    </x-form.group>
</x-form>
