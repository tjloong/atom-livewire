<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Storage Settings</x-slot>
        
        <div class="p-5">
            <x-input.select 
                wire:model="settings.filesystem" 
                :error="$errors->first('settings.filesystem')"
                :options="[
                    ['value' => 'local', 'label' => 'Local'],
                    ['value' => 'do', 'label' => 'Digital Ocean Spaces'],
                ]"
            >
                Storage Provider
            </x-input.select>

            <div>
                @if ($settings['filesystem'] === 'do')
                    <x-input.text wire:model.defer="settings.do_spaces_key" :error="$errors->first('settings.do_spaces_key')" required>
                        DO Spaces Key
                    </x-input.text>
        
                    <x-input.text wire:model.defer="settings.do_spaces_secret" :error="$errors->first('settings.do_spaces_secret')" required>
                        DO Spaces Secret
                    </x-input.text>
        
                    <x-input.text wire:model.defer="settings.do_spaces_region" :error="$errors->first('settings.do_spaces_region')" required>
                        DO Spaces Region
                    </x-input.text>
        
                    <x-input.text wire:model.defer="settings.do_spaces_bucket" :error="$errors->first('settings.do_spaces_bucket')" required>
                        DO Spaces Bucket
                    </x-input.text>
        
                    <x-input.text wire:model.defer="settings.do_spaces_endpoint" :error="$errors->first('settings.do_spaces_endpoint')" required>
                        DO Spaces Endpoint
                    </x-input.text>
        
                    <x-input.text wire:model.defer="settings.do_spaces_cdn" :error="$errors->first('settings.do_spaces_cdn')" required>
                        CDN URL
                    </x-input.text>                    
                @endif
            </div>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" color="green" icon="check">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>