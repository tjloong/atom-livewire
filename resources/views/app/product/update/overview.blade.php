<x-form header="Product Overview">
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
        class="grid gap-2 grid-cols-2"
    />

    <x-form.picker
        label="Category"
        wire:model="selected.categories"
        :options="data_get($this->options, 'categories')"
        :selected="data_get($this->selected, 'categories')"
        multiple
    />

    @if ($product->type !== 'variant')
        <x-form.amount
            label="Price"
            wire:model.defer="product.price"
            prefix="MYR"
        />

        <x-form.picker
            label="Taxes"
            wire:model="selected.taxes"
            :selected="data_get($this->selected, 'taxes')"
            :options="data_get($this->options, 'taxes')"
            multiple
        />
    @endif

    @if ($product->type === 'normal')
        <x-form.number
            label="Stock"
            wire:model.defer="product.stock"
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
