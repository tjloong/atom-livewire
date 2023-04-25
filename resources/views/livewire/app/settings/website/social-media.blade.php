<div class="w-full">
    <x-page-header title="Website Social Media"/>
    
    <x-form>
        <x-form.group>
            @foreach ($platforms as $platform)
                <x-form.text 
                    :label="str($platform)->headline()"
                    wire:model.defer="settings.{{ $platform }}"
                />
            @endforeach
        </x-form.group>
    </x-form>
</div>
