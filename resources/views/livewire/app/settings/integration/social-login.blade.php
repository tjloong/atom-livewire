<div class="max-w-screen-md">
    <x-page-header title="Social Login"/>
    
    <x-form>
        @foreach ($this->platforms as $item)
            <x-form.group cols="2" :label="data_get($this->platformLabels, $item)">
                <x-form.text wire:model.defer="settings.{{ $item }}_client_id" 
                    :label="data_get($this->clientIdLabels, $item)"/>

                <x-form.text wire:model.defer="settings.{{ $item }}_client_secret" 
                    :label="data_get($this->clientSecretLabels, $item)"/>
            </x-form.group>
        @endforeach
    </x-form>
</div>