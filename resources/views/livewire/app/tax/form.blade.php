<x-form>
    <x-form.group cols="2">
        <x-form.text wire:model.defer="tax.name" label="Tax Name"/>
        <x-form.number wire:model.defer="tax.rate" label="Tax Rate" postfix="%" step="0.01" min="0"/>
        <x-form.select.country wire:model="tax.country"/>
        <x-form.text wire:model.defer="tax.state"/>
    </x-form.group>

    <x-form.group>
        <x-form.checkbox wire:model="tax.is_active" label="Active"/>
    </x-form.group>
</x-form>
