<x-form>
    <x-form.group cols="2">
        <x-form.text wire:model.defer="person.name" label="Person Name"/>
        <x-form.select.label type="salutation" wire:model.defer="person.salutation"/>
        <x-form.text wire:model.defer="person.email"/>
        <x-form.text wire:model.defer="person.phone"/>
        <x-form.text wire:model.defer="person.designation"/>
    </x-form.group>
</x-form>
