<form wire:submit.prevent="submit">
    <x-box>
        <x-slot name="header">Social Media Pages</x-slot>

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
