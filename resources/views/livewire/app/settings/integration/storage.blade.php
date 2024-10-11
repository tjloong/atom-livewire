<div class="lg:max-w-xl space-y-6">
    <atom:_heading size="lg">Storage Configuration</atom:_heading>
    <atom:card>
        <atom:_form>
            <atom:_select wire:model="settings.filesystem" label="Storage Provider">
                <atom:option value="local">Local</atom:option>
                <atom:option value="do">Digital Ocean Spaces</atom:option>
            </atom:_select>

            @if (get($settings, 'filesystem') === 'do')
                <atom:_input wire:model.defer="settings.do_spaces_key" label="DO Spaces Key"/>
                <atom:_input wire:model.defer="settings.do_spaces_secret" label="DO Spaces Secret"/>
                <atom:_input wire:model.defer="settings.do_spaces_region" label="DO Spaces Region"/>
                <atom:_input wire:model.defer="settings.do_spaces_bucket" label="DO Spaces Bucket"/>
                <atom:_input wire:model.defer="settings.do_spaces_endpoint" label="DO Spaces Endpoint"/>
                <atom:_input wire:model.defer="settings.do_spaces_folder" label="DO Spaces Folder"/>
            @endif

            <atom:_button action="submit">Save</atom:_button>
        </atom:_form>
    </atom:card>
</div>
