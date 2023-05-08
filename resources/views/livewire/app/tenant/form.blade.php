<x-form>
    <x-form.group cols="2">
        <x-form.text wire:model.defer="tenant.name" label="Company Name"/>
        <x-form.email wire:model.defer="tenant.email" label="Contact Email"/>
        <x-form.phone wire:model.defer="tenant.phone" label="Contact Number"/>
        <x-form.text wire:model.defer="tenant.brn" label="BRN"/>
        <x-form.text wire:model.defer="tenant.website"/>
        <x-form.file wire:model="tenant.avatar_id" label="Logo" accept="image/*"/>
    </x-form.group>

    <x-form.group cols="2">
        <x-form.text wire:model.defer="tenant.address_1" label="Address Line 1"/>
        <x-form.text wire:model.defer="tenant.address_2" label="Address Line 2"/>
        <x-form.text wire:model.defer="tenant.city"/>
        <x-form.text wire:model.defer="tenant.zip" label="Postcode"/>
        <x-form.select.country wire:model="tenant.country"/>
        <x-form.select.state wire:model="tenant.state" :country="$tenant->country" uuid/>
    </x-form.group>
</x-form>