<x-box header="Social Login">
    <div 
        x-data="{
            activeProvider: null,
        }"
        class="flex flex-col divide-y"
    >
        @foreach ($this->providers as $provider)
            <div>
                <a 
                    x-on:click="activeProvider = @js($provider)"
                    x-bind:class="activeProvider === @js($provider) && 'bg-slate-100'"
                    class="p-4 flex items-center justify-between gap-3 hover:bg-slate-100"
                >
                    {{ data_get($this->providerLabels, $provider) }}
    
                    <x-icon name="chevron-down"/>
                </a>

                <div x-show="activeProvider === @js($provider)" class="p-4 flex flex-col gap-4">
                    <x-form.text 
                        :label="data_get($this->clientIdLabels, $provider)"
                        wire:model.defer="settings.{{ $provider }}_client_id"
                        :error="$errors->first('settings.'.$provider.'_client_id')"
                    />

                    <x-form.text 
                        :label="data_get($this->clientSecretLabels, $provider)"
                        wire:model.defer="settings.{{ $provider }}_client_secret"
                        :error="$errors->first('settings.'.$provider.'_client_secret')"
                    />

                    <div>
                        <x-button.submit type="button"
                            wire:click="submit('{{ $provider }}')"
                        />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-box>