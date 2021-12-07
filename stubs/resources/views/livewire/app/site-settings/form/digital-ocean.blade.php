<form wire:submit.prevent="save" class="max-w-lg">
    <x-box>
        <x-slot name="header">Digital Ocean Spaces Configurations</x-slot>

        <div class="p-5">
            <x-input.text wire:model.defer="settings.do_spaces_key" :error="$errors->first('settings.do_spaces_key')">
                DO Spaces Key
            </x-input.text>

            <x-input.text wire:model.defer="settings.do_spaces_secret" :error="$errors->first('settings.do_spaces_secret')">
                DO Spaces Secret
            </x-input.text>

            <x-input.text wire:model.defer="settings.do_spaces_region" :error="$errors->first('settings.do_spaces_region')">
                DO Spaces Region
            </x-input.text>

            <x-input.text wire:model.defer="settings.do_spaces_bucket" :error="$errors->first('settings.do_spaces_bucket')">
                DO Spaces Bucket
            </x-input.text>

            <x-input.text wire:model.defer="settings.do_spaces_endpoint" :error="$errors->first('settings.do_spaces_endpoint')">
                DO Spaces Endpoint
            </x-input.text>

            <x-input.text wire:model.defer="settings.do_spaces_cdn" :error="$errors->first('settings.do_spaces_cdn')">
                CDN URL
            </x-input.text>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>