<x-form>
    <x-form.text
        label="Variant Name"
        wire:model.defer="variant.name"
        :error="$errors->first('variant.name')"
        required
    />

    <x-form.field
        label="Variant Code"
        :error="$errors->first('variant.code')"
    >
        <div class="grid gap-2">
            <x-form.text wire:model.defer="variant.code"/>
            <div>
                <a wire:click="generateCode" class="text-sm inline-flex items-center gap-2">
                    <x-icon name="arrows-rotate" size="12px"/> Auto generate
                </a>
            </div>
        </div>
    </x-form.field>

    <x-form.amount
        label="Variant Price"
        wire:model.defer="variant.price"
    />

    <x-form.number
        label="Variant Stock"
        wire:model.defer="variant.stock"
    />

    <x-form.image
        label="Image"
        wire:model="variant.image_id"
        :placeholder="data_get($productVariant->image, 'url')"
    />

    <div class="grid gap-2">
        <x-form.checkbox wire:model="variant.is_default" label="This is default variant"/>
        <x-form.checkbox wire:model="variant.is_active" label="This variant is active"/>
    </div>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
