<x-form header="Website Social Media">
    @foreach ($platforms as $platform)
        <x-form.text 
            :label="str($platform)->headline()"
            wire:model.defer="settings.{{ $platform }}"
        />
    @endforeach

    <x-slot:foot>
        <x-button.submit/>
    </x-slot:foot>
</x-form>
