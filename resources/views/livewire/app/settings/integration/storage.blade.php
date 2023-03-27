<x-form header="Storage Settings">
    <x-form.group>
        <x-form.select wire:model="settings.filesystem" label="Storage Provider" :options="[
            ['value' => 'local', 'label' => 'Local'],
            ['value' => 'do', 'label' => 'Digital Ocean Spaces'],
        ]"/>
    
        @if (data_get($settings, 'filesystem') === 'do')
            <x-form.text wire:model.defer="settings.do_spaces_key" label="DO Spaces Key"/>
            <x-form.text wire:model.defer="settings.do_spaces_secret" label="DO Spaces Secret"/>
            <x-form.text wire:model.defer="settings.do_spaces_region" label="DO Spaces Region"/>
            <x-form.text wire:model.defer="settings.do_spaces_bucket" label="DO Spaces Bucket"/>
            <x-form.text wire:model.defer="settings.do_spaces_endpoint" label="DO Spaces Endpoint"/>
            <x-form.text wire:model.defer="settings.do_spaces_folder" label="DO Spaces Folder"/>
        @endif
    </x-form.group>
</x-form>
