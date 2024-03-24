<div class="max-w-screen-md">
    <x-heading title="settings.heading.social"/>
    
    <x-form>
        @foreach ($this->platforms as $item)
            <x-group cols="2" :heading="data_get($this->platformLabels, $item)">
                <x-form.text wire:model.defer="settings.{{ $item }}_client_id" 
                    :label="data_get($this->clientIdLabels, $item)"/>

                <x-form.text wire:model.defer="settings.{{ $item }}_client_secret" 
                    :label="data_get($this->clientSecretLabels, $item)"/>
            </x-group>
        @endforeach
    </x-form>
</div>