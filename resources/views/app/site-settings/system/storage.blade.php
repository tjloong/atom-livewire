<x-form header="Storage Settings">
    <x-form.select 
        label="Storage Provider"
        wire:model="settings.filesystem" 
        :error="$errors->first('settings.filesystem')"
        :options="[
            ['value' => 'local', 'label' => 'Local'],
            ['value' => 'do', 'label' => 'Digital Ocean Spaces'],
        ]"
    />

    @if ($settings['filesystem'] === 'do')
        <x-form.text 
            label="DO Spaces Key"
            wire:model.defer="settings.do_spaces_key" 
            :error="$errors->first('settings.do_spaces_key')" 
            required
        />

        <x-form.text 
            label="DO Spaces Secret"
            wire:model.defer="settings.do_spaces_secret" 
            :error="$errors->first('settings.do_spaces_secret')" 
            required
        />

        <x-form.text 
            label="DO Spaces Region"
            wire:model.defer="settings.do_spaces_region" 
            :error="$errors->first('settings.do_spaces_region')" 
            required
        />

        <x-form.text 
            label="DO Spaces Bucket"
            wire:model.defer="settings.do_spaces_bucket" 
            :error="$errors->first('settings.do_spaces_bucket')" 
            required
        />

        <x-form.text 
            label="DO Spaces Endpoint"
            wire:model.defer="settings.do_spaces_endpoint" 
            :error="$errors->first('settings.do_spaces_endpoint')" 
            required
        />

        <x-form.text 
            label="CDN URL"
            wire:model.defer="settings.do_spaces_cdn" 
            :error="$errors->first('settings.do_spaces_cdn')" 
            required
        />
    @endif

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
