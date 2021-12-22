<form wire:submit.prevent="save">
    <x-box>
        <div class="p-5">
            <x-input.text wire:model.defer="label.name" :error="$errors->first('label.name')" required>
                Label Name
            </x-input.text>

            <x-input.select
                wire:model.defer="label.type"
                :options="$types"
                :error="$errors->first('label.type')"
                required
            >
                Label Type
            </x-input.select>
        </div>

        <x-slot name="buttons">
            <x-button type="submit" icon="check" color="green">
                Save Label
            </x-button>
        </x-slot>
    </x-box>
</form>