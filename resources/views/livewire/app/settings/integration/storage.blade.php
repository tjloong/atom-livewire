<div class="max-w-screen-md">
    <x-heading title="Storage Settings" lg/>

    <x-form>
        <x-group cols="2">
            <x-select wire:model="settings.filesystem" label="Storage Provider" :options="[
                ['value' => 'local', 'label' => 'Local'],
                ['value' => 'do', 'label' => 'Digital Ocean Spaces'],
            ]"/>

            <div></div>

            @if (data_get($settings, 'filesystem') === 'do')
                <x-input wire:model.defer="settings.do_spaces_key" label="DO Spaces Key"/>
                <x-input wire:model.defer="settings.do_spaces_secret" label="DO Spaces Secret"/>
                <x-input wire:model.defer="settings.do_spaces_region" label="DO Spaces Region"/>
                <x-input wire:model.defer="settings.do_spaces_bucket" label="DO Spaces Bucket"/>
                <x-input wire:model.defer="settings.do_spaces_endpoint" label="DO Spaces Endpoint"/>
                <x-input wire:model.defer="settings.do_spaces_folder" label="DO Spaces Folder"/>
            @endif
        </x-group>
    </x-form>
</div>
