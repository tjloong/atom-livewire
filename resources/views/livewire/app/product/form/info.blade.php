<x-form :header="$header">
    <x-form.text
        label="Product Name"
        wire:model.defer="product.name"
        :error="$errors->first('product.name')"
        required
    />

    <x-form.field 
        label="Product Code"
        :error="$errors->first('product.code')"
    >
        <div class="grid gap-2">
            <x-form.text wire:model.defer="product.code"/>
            <div>
                <a wire:click="generateCode" class="text-sm inline-flex items-center gap-2">
                    <x-icon name="arrows-rotate" size="12px"/> Auto generate
                </a>
            </div>
        </div>
    </x-form.field>

    <x-form.checkbox-select
        label="Product Type"
        wire:model="product.type"
        :options="data_get($this->options, 'types')"
        class="grid gap-2 md:grid-cols-2"
    />

    <x-form.select
        label="Category"
        wire:model="selected.categories"
        :options="data_get($this->options, 'categories')"
        multiple
    >
        <x-slot:footlink 
            label="New Category" 
            :href="route('app.preferences', ['product-category'])"
        ></x-slot:footlink>
    </x-form.select>

    @if ($product->type === 'normal')
        <x-form.number
            label="Stock"
            wire:model.defer="product.stock"
        />
    @endif

    <x-form.select
        label="Taxes"
        wire:model="selected.taxes"
        :options="data_get($this->options, 'taxes')"
        multiple
    >
        <x-slot:footlink
            label="New Tax"
            :href="route('app.preferences', ['tax'])"
        ></x-slot:footlink>
    </x-form.select>

    @if ($product->type === 'variant')
        <x-form.field label="Price">
            <div class="flex items-center gap-2 text-sm">
                <x-icon name="circle-info" size="14px" class="text-gray-400"/>
                {{ __('Selling prices will follow each variant configuration.') }}
            </div>
        </x-form.field>
    @else
        <x-form.amount
            label="Price"
            wire:model.defer="product.price"
        />
    @endif

    <x-form.checkbox 
        wire:model="product.is_active"
        label="Product is active"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
