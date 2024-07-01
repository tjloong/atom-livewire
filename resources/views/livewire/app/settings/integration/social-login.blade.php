<div class="max-w-screen-md">
    <x-heading title="app.label.social-login" lg/>
    
    <x-form>
        @foreach ($this->providers as $provider)
            <x-group cols="2" :heading="get($provider, 'label')">
                <x-input wire:model.defer="settings.{{ get($provider, 'name') }}_client_id" label="Client ID"/>
                <x-input wire:model.defer="settings.{{ get($provider, 'name') }}_client_secret" label="Client Secret"/>
            </x-group>
        @endforeach
    </x-form>
</div>