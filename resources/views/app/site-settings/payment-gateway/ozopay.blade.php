<x-form header="Ozopay Settings">
    <x-form.text 
        label="Ozopay Terminal ID"
        wire:model.defer="settings.ozopay_tid" 
        :error="$errors->first('settings.ozopay_tid')" 
        required
    />

    <x-form.text 
        label="Ozopay Secret"
        wire:model.defer="settings.ozopay_secret" 
        :error="$errors->first('settings.ozopay_secret')" 
        required
    />

    <x-form.text 
        label="Ozopay URL"
        wire:model.defer="settings.ozopay_url" 
        :error="$errors->first('settings.ozopay_url')" 
        required
    />

    <x-form.text 
        label="Ozopay Sandbox URL"
        wire:model.defer="settings.ozopay_sandbox_url"
    />

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>