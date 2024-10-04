<div class="lg:max-w-xl space-y-6">
    <atom:_heading size="lg">Storage Configuration</atom:_heading>
    <atom:card>
        <atom:_form>
            <x-select wire:model="settings.filesystem" label="Storage Provider" :options="[
                ['value' => 'local', 'label' => 'Local'],
                ['value' => 'do', 'label' => 'Digital Ocean Spaces'],
            ]"/>

            @if (get($settings, 'filesystem') === 'do')
                <x-input wire:model.defer="settings.do_spaces_key" label="DO Spaces Key"/>
                <x-input wire:model.defer="settings.do_spaces_secret" label="DO Spaces Secret"/>
                <x-input wire:model.defer="settings.do_spaces_region" label="DO Spaces Region"/>
                <x-input wire:model.defer="settings.do_spaces_bucket" label="DO Spaces Bucket"/>
                <x-input wire:model.defer="settings.do_spaces_endpoint" label="DO Spaces Endpoint"/>
                <x-input wire:model.defer="settings.do_spaces_folder" label="DO Spaces Folder"/>
            @endif

            <atom:_button action="submit">Save</atom:_button>
        </atom:_form>
    </atom:card>
</div>
