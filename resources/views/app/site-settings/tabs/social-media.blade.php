<form wire:submit.prevent="submit" class="max-w-lg">
    <x-box>
        <div class="p-5">
            @foreach ($platforms as $platform)
                <x-input.text wire:model.defer="settings.{{ $platform }}">
                    <div class="flex items-center gap-1">
                        <x-icon name="{{ $platform }}" type="logo" size="20px"/>
                        <div>{{ Str::headline($platform) }}</div>
                    </div>
                </x-input.text>
            @endforeach
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save
            </x-button>
        </x-slot>
    </x-box>
</form>
