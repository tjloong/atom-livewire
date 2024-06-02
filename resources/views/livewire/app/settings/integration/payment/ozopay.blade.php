<form wire:submit.prevent="submit" class="p-5 grid gap-6">
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

    <div>
        <x-button action="submit"/>
    </div>
</form>