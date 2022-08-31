<x-form>
    <x-form.text
        label="Variant Name"
        wire:model.defer="productVariant.name"
        :error="$errors->first('productVariant.name')"
        required
    />

    <x-form.field
        label="Variant Code"
        :error="$errors->first('productVariant.code')"
    >
        <div class="grid gap-2">
            <x-form.text wire:model.defer="productVariant.code"/>
            <div>
                <a wire:click="generateCode" class="text-sm inline-flex items-center gap-2">
                    <x-icon name="arrows-rotate" size="12px"/> Auto generate
                </a>
            </div>
        </div>
    </x-form.field>

    <x-form.amount
        label="Price"
        wire:model.defer="productVariant.price"
        prefix="MYR"
    />

    <x-form.number
        label="Stock"
        wire:model.defer="productVariant.stock"
    />

    <x-form.image
        label="Image"
        wire:model="productVariant.image_id"
        :placeholder="data_get($productVariant->image, 'url')"
    />

    <div class="grid gap-2">
        <x-form.checkbox wire:model="productVariant.is_default" label="This is default variant"/>
        <x-form.checkbox wire:model="productVariant.is_active" label="This variant is active"/>
    </div>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
