<x-form>
    <x-form.group cols="2">
        <x-form.text wire:model.defer="variant.name" label="Variant Name"/>
    
        <x-form.field label="Variant Code" name="variant.code">
            <x-form.text wire:model.defer="variant.code" :label="false">
                <x-slot:button wire:click="generateCode" icon="refresh"></x-slot:button>
            </x-form.text>
        </x-form.field>

        <x-form.number wire:model.defer="variant.price"/>
        <x-form.number wire:model.defer="variant.stock"/>
        <x-form.file wire:model="variant.image_id" accept="image/*"/>
    </x-form.group>

    <x-form.group>
        <x-form.checkbox wire:model="variant.is_default" label="This is default variant"/>
        <x-form.checkbox wire:model="variant.is_active" label="This variant is active"/>
    </x-form.group>
</x-form>
