<x-form modal id="announcement-modal" header="Announcement">
    <x-form.group cols="2">
        <x-form.text wire:model.defer="inputs.title"/>
        <x-form.select wire:model="inputs.type" :options="data_get($this->options, 'types')"/>
            
        @if (($cats = data_get($this->options, 'categories')) && count($cats) > 1)
            <x-form.select wire:model="inputs.category" :options="$cats"/>
        @endif
    </x-form.group>

    @if (data_get($inputs, 'type') === 'link')
        <x-form.group cols="2" label="External Link">
            <x-form.text wire:model.defer="inputs.href" label="Link"/>
            <x-form.text wire:model.defer="inputs.cta" label="Link Text"/>
        </x-form.group>
    @elseif (data_get($inputs, 'type') === 'popup')
        <x-form.group label="Pop-Up Content">
            <x-form.richtext wire:model.defer="inputs.content" :label="false"/>
        </x-form.group>
    @endif

    <x-form.group>
        <x-form.checkbox wire:model="inputs.is_active" label="Announcement is active"/>
    </x-form.group>
</x-form>