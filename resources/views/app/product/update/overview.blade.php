<x-form header="Product Overview">
    @if ($product->exists)
        <x-form.field label="Product Type">
            {{ str()->headline($product->type) }}
        </x-form.field>
    @endif

    <x-form.text
        label="Product Name"
        wire:model.defer="product.name"
        :error="$errors->first('product.name')"
        required
    />

    <x-form.text
        label="Product Code"
        wire:model.defer="product.code"
        :error="$errors->first('product.code')"
        caption="Leave empty to auto generate"
    />

    @if (!$product->exists)
        <x-form.select
            label="Product Type"
            wire:model="product.type"
            :options="data_get($this->options, 'types')"
            :selected="$product->type"
            :error="$errors->first('product.type')"
            required
        />
    @endif

    <x-form.picker
        label="Category"
        wire:model="categories"
        :options="data_get($this->options, 'categories')"
        :selected="$categories"
        multiple
    />

    @if ($product->type !== 'variant')
        <x-form.picker
            label="Tax"
            wire:model="product.tax_id"
            :selected="$product->tax_id"
            :options="data_get($this->options, 'taxes')"
        />

        <x-form.amount
            label="Price"
            wire:model.defer="product.price"
            prefix="MYR"
        />
    @endif

    @if ($product->type === 'normal')
        <x-form.number
            label="Stock"
            wire:model.defer="product.stock"
        />
    @endif

    <x-form.checkbox wire:model="product.is_active" label="Product is active"/>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
