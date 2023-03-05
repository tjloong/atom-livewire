<x-form :header="$this->title">
    <x-form.group cols="2">
        <x-form.text wire:model.defer="contact.name" :label="str($this->contact->category)->title()->toString().' Name'"/>

        @if ($contact->exists) 
            <x-form.field label="Contact Type" :value="str($this->contact->type)->title()"/>
            <x-form.select.owner wire:model="contact.owned_by" label="Owner"/>
        @else
            <x-form.select wire:model="contact.type" label="Contact Type" :options="data_get($this->options, 'types')"/>
        @endif

        <x-form.field :label="['person' => 'Avatar', 'company' => 'Logo'][$contact->type]">
            @if ($contact->avatar_id) <x-thumbnail :file="$contact->avatar_id" wire:remove="$set('contact.avatar_id', null)"/>
            @else <x-form.file wire:model="contact.avatar_id" accept="image/*" :library="false" :youtube="false"/>
            @endif
        </x-form.field>
    </x-form.group>

    <x-form.group cols="2">
        <x-form.text wire:model.defer="contact.email"/>
        <x-form.text wire:model.defer="contact.phone"/>

        @if ($contact->type === 'company')
            <x-form.text wire:model.defer="contact.fax"/>
            <x-form.text wire:model.defer="contact.brn"/>
            <x-form.text wire:model.defer="contact.tax_number"/>
            <x-form.text wire:model.defer="contact.website"/>
        @endif
    </x-form.group>

    <x-form.group cols="2">
        <x-form.text wire:model.defer="contact.address_1" label="Address Line 1"/>
        <x-form.text wire:model.defer="contact.address_2" label="Address Line 2"/>
        <x-form.text wire:model.defer="contact.city"/>
        <x-form.text wire:model.defer="contact.zip" label="Postcode"/>

        <x-form.select.country wire:model="contact.country"/>
        @if ($country = $contact->country)
            <x-form.select.state wire:model="contact.state" :country="$country" uuid/>
        @endif
    </x-form.group>
</x-form>
