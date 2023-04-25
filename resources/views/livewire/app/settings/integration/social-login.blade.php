<div class="w-full">
    <x-page-header title="Social Login"/>
    
    <x-form>
        <div class="flex flex-col divide-y">
            @foreach ($this->platforms as $item)
                <div class="flex flex-col">
                    <a 
                        wire:click="$set('platform', @js($item))"
                        class="p-4 flex items-center gap-3 hover:bg-slate-100 {{ $this->platform === $item ? 'bg-slate-100' : '' }}"
                    >
                        <span class="grow">{{ data_get($this->platformLabels, $item) }}</span> 
                        <x-icon name="{{ $this->platform === $item ? 'chevron-up' : 'chevron-down' }}"/>
                    </a>
    
                    @if ($this->platform === $item)
                        <x-form.group cols="2">
                            <x-form.text wire:model.defer="settings.{{ $item }}_client_id" :label="data_get($this->clientIdLabels, $item)"/>
                            <x-form.text wire:model.defer="settings.{{ $item }}_client_secret" :label="data_get($this->clientSecretLabels, $item)"/>
                        </x-form.group>
                    @endif
                </div>
            @endforeach
        </div>
    
        @if (!$this->platform)
            <x-slot:foot></x-slot:foot>
        @endif
    </x-form>
</div>