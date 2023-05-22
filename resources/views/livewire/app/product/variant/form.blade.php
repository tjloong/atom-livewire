<x-form>
    <x-form.group cols="2">
        <x-form.text wire:model.defer="variant.name" label="Variant Name"/>

        <x-form.text wire:model.defer="variant.code" label="Variant Code">
            <x-slot:button icon="refresh" wire:click="generateCode"></x-slot:button>
        </x-form.text>

        <x-form.number wire:model.defer="variant.price"/>
        <x-form.number wire:model.defer="variant.stock"/>
        <x-form.file wire:model="variant.image_id" accept="image/*"/>
    </x-form.group>

    <x-form.group>
        <div>
            <x-form.checkbox wire:model="variant.is_default" label="Default"/>
            <x-form.checkbox wire:model="variant.is_active" label="Active"/>
        </div>
    </x-form.group>
</x-form>
