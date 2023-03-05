<x-form header="Website Social Media">
    <x-form.group>
        @foreach ($platforms as $platform)
            <x-form.text 
                :label="str($platform)->headline()"
                wire:model.defer="settings.{{ $platform }}"
            />
        @endforeach
    </x-form.group>
</x-form>
