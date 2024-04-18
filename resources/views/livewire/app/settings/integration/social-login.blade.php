<div class="max-w-screen-md">
    <x-heading title="app.label.social-login"/>
    
    <x-form>
        @foreach ($this->providers as $provider)
            <x-group cols="2" :heading="get($provider, 'label')">
                <x-form.text wire:model.defer="settings.{{ get($provider, 'name') }}_client_id" label="Client ID"/>
                <x-form.text wire:model.defer="settings.{{ get($provider, 'name') }}_client_secret" label="Client Secret"/>
            </x-group>
        @endforeach
    </x-form>
</div>