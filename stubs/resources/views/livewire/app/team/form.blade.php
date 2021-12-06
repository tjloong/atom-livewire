<form wire:submit.prevent="save">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="team.name" :error="$errors->first('team.name')" required>
                Team Name
            </x-input.text>

            <x-input.textarea wire:model.defer="team.description">
                Description
            </x-input.textarea>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green" wire:loading.class="loading">
                Save Team
            </x-button>
        </x-slot>
    </x-box>
</form>
