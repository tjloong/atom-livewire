<x-form>
    <x-form.text
        label="Variant Name"
        wire:model.defer="variant.name"
        :error="$errors->first('variant.name')"
        required
    />

    <x-form.amount
        label="Price"
        wire:model.defer="variant.price"
        prefix="MYR"
    />

    <x-form.number
        label="Stock"
        wire:model.defer="variant.stock"
    />

    <x-form.image
        label="Image"
        wire:model="variant.image_id"
        :placeholder="optional($variant->image)->url"
    />

    <div class="grid gap-2">
        <x-form.checkbox wire:model="variant.is_default" label="This is default variant"/>
        <x-form.checkbox wire:model="variant.is_active" label="This variant is active"/>
    </div>

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
